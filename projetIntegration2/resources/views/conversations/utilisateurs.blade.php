<div class="list-group">
    @foreach ($users as $user)
        <a class="list-group-item" href=" {{route('conversations.show', $user->id)}}">
            {{$user->email}}
        </a>
    @endforeach
</div>