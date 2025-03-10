<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clan;
use App\Models\User;
use App\Models\UtilisateurClan;
use App\Models\Message;
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
            'users' => \DB::table('demande_amis')
            ->join('users', 'requested_id', '=', 'users.id')
            ->select('users.email', 'users.id')
            ->where('demande_amis.status', 'accepted')
            ->get(),
        ]);

    }

    public function show(User $user){

        return view('conversations.show',[
            'users' => \DB::table('demande_amis')
            ->join('users', 'requested_id', '=', 'users.id')
            ->select('users.email', 'users.id', 'users.email')
            ->where('demande_amis.status', 'accepted')
            ->get(),
            'user' => $user,
            'messages' => $this->ConvRepository->getMessageFor(auth()->id(), $user->id)->paginate(300)//Pagination des messages par 2
        ]);
    }

    public function broadcast(Request $request)
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
            $lastId = \DB::table('user_ami')->insertGetId([
                'idEnvoyer' => auth()->id(),
                'idReceveur'    => $request->to,
                'message'   => $request->message,
                'fichier'   => $fichierNom, // Stocke le chemin public
                'created_at'=> now(),
                'updated_at'=> now()
            ]);
    
            // Diffuser l’événement via Pusher
            broadcast(new PusherBroadcast($request->message, auth()->id(), $request->to, false, $lastId, $fichierNom, auth()->user()->email))
                ->toOthers();
    
        } catch (\Exception $e) {
            \Log::error('❌ Erreur lors du broadcast: ' . $e->getMessage());
        }
    
        return response()->json([
            'message'      => $request->message,
            'last_id'      => $lastId,
            'sender_id'    => auth()->id(),
            'sender_email' => auth()->user()->email,
            'fichier'      => $fichierNom ? asset($dossier . $fichierNom) : null, // Retourne l'URL complète
            'email'        => auth()->user()->email,

        ]);
    }
    

    public function receive(Request $request){

    return response()->json([
        'message' => $request->message,
        'sender_id' => $request->sender_id,
        'group_id' => $request->group_id,
        'canal_id' => $request->canal_id,
        'deleted' => $request->deleted,
        'last_id' => $request->last_id,
        'photo' => $request->photo,
        
    ]);
    }

    public function destroy(Message $message)
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
