<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Repository\ConversationsRepository;
use App\Http\Requests\StoreMessage;
use App\Events\PusherBroadcast;




class Conversations extends Controller
{

    /**
    * @var ConversationsRepository
    */

    private $ConvRepository;

    public function __construct(ConversationsRepository $conversationRepository){
        $this->ConvRepository = $conversationRepository;
    }


    public function index(){

        $users = User::select('email','id')->get();
        return view('conversations.index',[
            'users' => $this->ConvRepository->getConversations()
        ]);

    }

    public function show(User $user){
        //dd($user);
        $users = auth()->id();
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
    
}
