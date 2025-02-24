<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clan;
use App\Models\User;
use App\Models\UtilisateurClan;
use App\Repository\ConversationsRepository;
use App\Repository\ConversationsClan;
use App\Http\Requests\StoreMessage;
use App\Events\PusherBroadcast;
use App\Events\MessageGroup;
use App\Events\SuppressionMessageGroup;





class Conversations extends Controller
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











    public function destroy(UtilisateurClan $message)
    {
        //\Log::info('Début de la suppression du message', ['message_id' => $message->id, 'id_envoyer' => $message->idEnvoyer]);
    
        // Vérification des droits d'accès de l'utilisateur
        if (auth()->id() !== $message->idEnvoyer) {
            //\Log::warning('Action non autorisée', ['user_id' => auth()->id(), 'message_sender_id' => $message->idEnvoyer]);
            return response()->json(['error' => 'Action non autorisée'], 403);
        }
    
        //\Log::info('Utilisateur autorisé à supprimer le message', ['user_id' => auth()->id()]);
    
        // Récupérer l'URL de l'image associée au message
        $imageUrl = $message->photo; // Remplacez `image` par le nom réel de l'attribut qui contient l'URL de l'image
        //\Log::info('URL de l\'image associée', ['image_url' => $imageUrl]);
    
        // Si l'image existe, la supprimer
        if ($imageUrl) {
            // Extraire le nom du fichier à partir de l'URL
            $fileName = basename($imageUrl); // Exemple : '1740422612_IMG_4164.PNG'
            //\Log::info('Nom du fichier extrait de l\'URL', ['file_name' => $fileName]);
    
            // Récupérer le chemin complet du fichier
            $filePath = public_path('img/conversations_photo/' . $fileName);
            //\Log::info('Chemin complet du fichier', ['file_path' => $filePath]);
    
            // Vérifier si le fichier existe et le supprimer
            if (file_exists($filePath)) {
                unlink($filePath);
                //\Log::info('Fichier supprimé', ['file_path' => $filePath]);
            } else {
                //\Log::warning('Fichier non trouvé pour suppression', ['file_path' => $filePath]);
            }
        } else {
            //\Log::info('Aucune image à supprimer pour ce message');
        }
    
        // Supprimer le message du modèle
        $messageId = $message->id;
        $message->delete();
        //\Log::info('Message supprimé', ['message_id' => $messageId]);
    
        // Diffuser l'événement de suppression
        broadcast(new SuppressionMessageGroup($messageId, $message->idClan))->toOthers();
        //\Log::info('Événement de suppression diffusé', ['message_id' => $messageId, 'clan_id' => $message->idClan]);
    
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
        //SI IL Y A UN BUG AVEC PHOTO C'EST ICI
        $request->validate([
            'message' => 'nullable|string',
            'photo'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        try {
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                
                // Définir le chemin de stockage
                $destinationPath = public_path('img/conversations_photo');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true); // Crée le dossier s'il n'existe pas
                }
    
                // Générer un nom unique
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->move($destinationPath, $photoName);
    
                // Construire le chemin d'accès public
                $photoUrl = asset("img/conversations_photo/{$photoName}");
            } else {
                $photoUrl = null;
            }
    
            // Insérer le message dans la base de données
            $lastId = \DB::table('utilisateur_clan')->insertGetId([
                'idEnvoyer' => auth()->id(),
                'idClan'    => $request->to,
                'message'   => $request->message,
                'photo'     => $photoUrl, // Stocke le chemin public
                'created_at'=> now(),
                'updated_at'=> now()
            ]);
    
            // Diffuser l’événement via Pusher
            broadcast(new MessageGroup($request->message, auth()->id(), $request->to, false, $lastId, $photoUrl))
                ->toOthers();
    
        } catch (\Exception $e) {
            \Log::error('❌ Erreur lors du broadcast: ' . $e->getMessage());
        }
    
        return response()->json([
            'message'      => $request->message,
            'last_id'      => $lastId,
            'sender_id'    => auth()->id(),
            'sender_email' => auth()->user()->email,
            'photo'        => $photoUrl // Retourne l'URL complète
        ]);
    }
    
    
    


    public function receiveClan(Request $request){
        //\Log::info('Receive method called with message: ' . $request->message);
        //\Log::info('Message received: ' . $request->message); // Debug
        return response()->json(['message' => $request->message]);
    }








    
}
