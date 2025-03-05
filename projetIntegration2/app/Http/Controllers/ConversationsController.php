<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clan;
use App\Models\User;
use App\Models\UtilisateurClan;
use App\Models\Canal;
use App\Repository\ConversationsRepository;
use App\Repository\ConversationsClan;
use App\Http\Requests\StoreMessage;
use App\Events\PusherBroadcast;
use App\Events\MessageGroup;
use App\Events\SuppressionMessageGroup;





class ConversationsController extends Controller
{

    private $ConvRepository;
    private $ClanRepository;

    public function __construct(
        ConversationsRepository $conversationRepository, 
        ConversationsClan $ClanRepository
    ) {
        $this->ConvRepository = $conversationRepository;
        $this->ClanRepository = $ClanRepository;
    }


    public function index(){

        $users = User::select('email','id')->get();
        return view('conversations.index',[
            'users' => $this->ConvRepository->getConversations()
        ]);

    }

    public function show(User $user){
        dd($user);
        //$users = auth()->id();
        //dd($user);
        return view('conversations.show',[
            'users' => $this->ConvRepository->getConversations(),
            'user' => $user,
            'messages' => $this->ConvRepository->getMessageFor(auth()->id(), $user->id)->paginate(300)//Pagination des messages par 2
        ]);
    }


    public function store(User $user, StoreMessage $request){
        $senderId = auth()->id();
        $receiverId = $user->id;

        $message = $this->ConvRepository->createMessage(
            $request->get('content'),
            $senderId,
            $receiverId
        );

        // Envoi du message via Pusher
        broadcast(new PusherBroadcast($message->content, $senderId, $receiverId))->toOthers();
        //\Log::info("📡 Message broadcasté : {$message->content}");

        return redirect()->route('conversations.show', [$user->id]);
    }



    public function broadcast(Request $request){
        //\Log::info('Message envoyé via Pusher', $request->all());
        //\Log::info('📡 Tentative de broadcast avec message: ' . $request->message);
        try {
            broadcast(new PusherBroadcast($request->message, auth()->id(), $request->to))
                ->toOthers();
            //\Log::info('✅ Message broadcasté avec succès');
            
            // Enregistrement des informations dans la table user_ami
            \DB::table('user_ami')->insert([
                'idEnvoyer' => auth()->id(),
                'idReceveur' => $request->to,
                'message' => $request->message,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            //\Log::info('✅ Message Enregistrer avec succès');

            


        } catch (\Exception $e) {
            \Log::error('❌ Erreur lors du broadcast: ' . $e->getMessage());
        }
        return response()->json(['message' => $request->message]);
    }
    

    public function receive(Request $request){
        //\Log::info('Receive method called with message: ' . $request->message);
        //\Log::info('Message received: ' . $request->message); // Debug
        return response()->json(['message' => $request->message]);
    }









/*-----------------------------------Conversation Clan-----------------------------------*/

    public function destroy(UtilisateurClan $message)
    {
        if (auth()->id() !== $message->idEnvoyer) {
            return response()->json(['error' => 'Action non autorisée'], 403);
        }
    
        \Log::info('Détails du message avant suppression', ['message_id' => $message->id, 'fichier' => $message->fichier]);
    
        if ($message->fichier) {
            $fichierNom = $message->fichier;
    
            // Déterminer le dossier selon l'extension
            $dossier = in_array(pathinfo($fichierNom, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])
                ? 'img/conversations_photo/'
                : 'fichier/conversations_fichier/';
    
            $fichierPath = public_path($dossier . $fichierNom);
    
            \Log::info('Chemin du fichier à supprimer', ['fichier_path' => $fichierPath]);
    
            if (file_exists($fichierPath)) {
                unlink($fichierPath);
                \Log::info('Fichier supprimé', ['fichier_path' => $fichierPath]);
            } else {
                \Log::warning('Le fichier n\'existe pas', ['fichier_path' => $fichierPath]);
            }
        } else {
            \Log::info('Aucun fichier associé au message', ['message_id' => $message->id]);
        }
    
        $messageId = $message->id;
        $message->delete();
    
        broadcast(new SuppressionMessageGroup($messageId, $message->idClan))->toOthers();
    
        return response()->json(['success' => 'Message supprimé']);
    }
    
    
    
    
    
    

    public function showClan(Clan $clans)
    {
        
        return view('conversations.showClan', [
            'users' => $this->ClanRepository->getConversationsClan(),
            'user' => $clans,
            'messages' => $this->ClanRepository->getMessageClanFor($clans->id) // Plus besoin de auth()->id()
            
        ]);
        
    }
    

    public function broadcastClan(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'fichier' => 'nullable|file|max:20480', // 20 Mo
        ]);
        
        if (!$request->filled('message') && !$request->hasFile('fichier')) {
            return response()->json(['error' => 'Vous devez envoyer soit un message, soit un fichier, soit les deux.'], 422);
        }
        
    
        try {
            $fichierNom = null;
            if ($request->hasFile('fichier')) {
                $fichier = $request->file('fichier');
        
                // Générer un nom unique avec horodatage
                $fichierNom = time() . '_' . $fichier->getClientOriginalName();
        
                // Déterminer le dossier en fonction du type de fichier
                $dossier = in_array($fichier->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])
                    ? 'img/conversations_photo/'
                    : 'fichier/conversations_fichier/';
        
                // Stocker le fichier
                $fichier->move(public_path($dossier), $fichierNom);
            }
    
            // Insérer le message dans la base de données
            $lastId = \DB::table('utilisateur_clan')->insertGetId([
                'idEnvoyer' => auth()->id(),
                'idClan'    => $request->to,
                'message'   => $request->message,
                'fichier'   => $fichierNom, // Stocke le chemin public
                'created_at'=> now(),
                'updated_at'=> now()
            ]);
    
            // Diffuser l’événement via Pusher
            broadcast(new MessageGroup($request->message, auth()->id(), $request->to, false, $lastId, $fichierNom))
                ->toOthers();
    
        } catch (\Exception $e) {
            \Log::error('❌ Erreur lors du broadcast: ' . $e->getMessage());
        }
    
        return response()->json([
            'message'      => $request->message,
            'last_id'      => $lastId,
            'sender_id'    => auth()->id(),
            'sender_email' => auth()->user()->email,
            'fichier'      => $fichierNom ? asset($dossier . $fichierNom) : null // Retourne l'URL complète
        ]);
    }
    
    
    


    public function receiveClan(Request $request){
        //\Log::info('Receive method called with message: ' . $request->message);
        //\Log::info('Message received: ' . $request->message); // Debug
        return response()->json(['message' => $request->message]);
    }













    //Modification pour avoir mes points

    public function showModificationMessage(){
        $messages = \DB::table('utilisateur_clan')
            ->select('id', 'message', 'created_at', 'fichier')
            ->where('idEnvoyer', auth()->id())
            ->get();

        return view('conversations.modification',[
            'messages' => $messages
        ]);
    }

    public function updateMessage(Request $request, $id)
    {
        $request->validate([
            'new_message' => 'required|string',
        ]);

        $message = UtilisateurClan::findOrFail($id);

        if (auth()->id() !== $message->idEnvoyer) {
            return response()->json(['error' => 'Action non autorisée'], 403);
        }

        $message->message = $request->new_message;
        $message->save();

        return redirect()->route('conversations.showModificationMessage');
    }

}
