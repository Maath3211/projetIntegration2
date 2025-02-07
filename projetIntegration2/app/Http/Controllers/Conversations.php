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
        $users = User::select('email','id')->get();
        return view('conversations.show',[
            'users' => $this->ConvRepository->getConversations(),
            'user' => $user,
            'messages' => $this->ConvRepository->getMessageFor(1, $user->id)->paginate(50) //Pagination des messages par 2
        ]);
    }


    public function store(User $user, StoreMessage $request){
        $message = $this->ConvRepository->createMessage(
            $request->get('content'),
            1,
            $user->id
        );
        broadcast(new PusherBroadcast($message->content))->toOthers();
        \Log::info('Message created and broadcasted: ' . $message->content); // Log added
        return redirect()->route('conversations.show', [$user->id]);
    }



    public function broadcast(Request $request){
        \Log::info('📡 Tentative de broadcast avec message: ' . $request->message);
        try {
            broadcast(new PusherBroadcast($request->message))->toOthers();
            \Log::info('Broadcasting message to Pusher: ' . $request->message); // Log added for debugging
            \Log::info('✅ Message broadcasté avec succès');
        } catch (\Exception $e) {
            \Log::error('❌ Erreur lors du broadcast: ' . $e->getMessage());
        }
        return response()->json(['message' => $request->message]);
    }
    

    public function receive(Request $request){
        \Log::info('Receive method called with message: ' . $request->message);
        \Log::info('Message received: ' . $request->message); // Debug
        return response()->json(['message' => $request->message]);
    }
    
}
