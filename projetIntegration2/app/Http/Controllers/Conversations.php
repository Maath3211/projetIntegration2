<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clan;
use App\Models\User;
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

    public function index(Request $request)
    {
        $conversations = $this->ConvRepository->getConversations();

        if ($request->wantsJson()) {
            return response()->json(['conversations' => $conversations]);
        }
        
        return view('conversations.index', [
            'users' => $conversations
        ]);
    }

    public function show(Request $request, User $user)
    {
        $messages = $this->ConvRepository
            ->getMessageFor(auth()->id(), $user->id)
            ->paginate(300);

        if ($request->wantsJson()) {
            return response()->json([
                'conversations' => $this->ConvRepository->getConversations(),
                'user' => $user,
                'messages' => $messages
            ]);
        }
        
        return view('conversations.show', [
            'users' => $this->ConvRepository->getConversations(),
            'user' => $user,
            'messages' => $messages
        ]);
    }

    public function store(Request $request, User $user, StoreMessage $formRequest)
    {
        $senderId = auth()->id();
        $receiverId = $user->id;

        $message = $this->ConvRepository->createMessage(
            $formRequest->get('content'),
            $senderId,
            $receiverId
        );

        broadcast(new PusherBroadcast($message->content, $senderId, $receiverId))->toOthers();

        if ($request->wantsJson()) {
            return response()->json(['message' => $message]);
        }
        
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
            broadcast(new MessageGroup($request->message, auth()->id(),$request->to))
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
        return response()->json(['message' => $request->message]);
    }


    public function receiveClan(Request $request){
        //\Log::info('Receive method called with message: ' . $request->message);
        //\Log::info('Message received: ' . $request->message); // Debug
        return response()->json(['message' => $request->message]);
    }
}
