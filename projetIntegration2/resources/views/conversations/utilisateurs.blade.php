<div class="list-group">
    @foreach ($users as $user)
        <a class="list-group-item" href=" {{route('conversations.show', $user->id)}}">
            {{$user->email}}

            @if (isset($user->unread))
                <span class="badge bg-primary rounded-pill">
                    {{-- Affiche message non lus --}}
                    {{$user->unread}}
                </span>
            @endif
        </a>
    @endforeach
</div>