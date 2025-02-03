<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Repository\ConversationsRepository;



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
            'user' => $user
        ]);
    }


    public function store(User $user, Request $request){
        $this->ConvRepository->createMessage(
            $request->get('content'),
            1,
            $user->id
        );
        return redirect()->route('conversations.show', ['id' => $user->id]);
    }
}
