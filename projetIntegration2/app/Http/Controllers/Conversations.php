<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Repository\ConversationsRepository;
use App\Http\Requests\StoreMessage;




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
            'messages' => $this->ConvRepository->getMessageFor(1, $user->id)->get()->reverse()
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
}
