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
        $this->ConvRepository->createMessage(
            $request->get('content'),
            1,
            $user->id
        );
        return redirect()->route('conversations.show', [$user->id]);
    }



    public function broadcast(Request $request){
        broadcast(new PusherBroadcast($request->message))->toOthers();
        return view('conversations.broadcast', ['messages' => $request->message]);
    }
    

    public function receive(Request $request){
        return view('conversations.receive', ['messages' => $request->message]);
    }
    
}
