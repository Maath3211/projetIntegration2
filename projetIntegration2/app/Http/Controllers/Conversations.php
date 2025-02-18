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
        // Afficher l'ID dans la console
        $lastId = \DB::table('utilisateur_clan')->insertGetId([
            'idEnvoyer' => auth()->id(),
            'idClan'    => $request->to,
            'message'   => $request->message,
            'created_at'=> now(),
            'updated_at'=> now()
        ]);
        if (auth()->id() !== $message->idEnvoyer) {
            return response()->json(['error' => 'Action non autorisée'], 403);
        }
    
        // Stocke l'ID avant suppression
        $messageId = $message->id;
        $message->delete();
    
        // Diffuser l'événement de suppression via Pusher
        broadcast(new MessageGroup($messageId, auth()->id(), $message->idClan, true, $lastId))->toOthers();
    
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
    

    public function broadcastClan(Request $request){

        //\Log::info('Message envoyé via Pusher', $request->all());
        //\Log::info('📡 Tentative de broadcast avec message: ' . $request->message);
        try {

            // Afficher l'ID dans la console
            $lastId = \DB::table('utilisateur_clan')->insertGetId([
                'idEnvoyer' => auth()->id(),
                'idClan'    => $request->to,
                'message'   => $request->message,
                'created_at'=> now(),
                'updated_at'=> now()
            ]);
            
            \Log::info('ID de la table utilisateur_clan: ' . $lastId);

            broadcast(new MessageGroup($request->message, auth()->id(),$request->to, false, $lastId))
                ->toOthers();
            //\Log::info('✅ Message broadcasté avec succès');
            
            // Enregistrement des informations dans la table user_ami
            \DB::table('utilisateur_clan')->insert([
                'idEnvoyer' => auth()->id(),
                'idClan' => $request->to,
                'message' => $request->message,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            //\Log::info('✅ Message Enregistrer avec succès');



        } catch (\Exception $e) {
            //\Log::error('❌ Erreur lors du broadcast: ' . $e->getMessage());
        }
        return response()->json(['message' => $request->message, 'last_id' => $lastId]);
    }


    public function receiveClan(Request $request){
        //\Log::info('Receive method called with message: ' . $request->message);
        //\Log::info('Message received: ' . $request->message); // Debug
        return response()->json(['message' => $request->message]);
    }








    
}
