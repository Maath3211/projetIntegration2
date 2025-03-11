<div class="list-group">
    @foreach ($users as $user)
        @if ($user->id !== auth()->id())
            <a class="list-group-item" href=" {{route('conversations.show', $user->id)}}">
                {{$user->email}}

                @if (isset($user->unread))
                    <span class="badge bg-primary rounded-pill">
                        {{-- Affiche message non lus --}}
                        {{$user->unread}}
                    </span>
                @endif
            </a>
        @endif
    @endforeach
</div>
