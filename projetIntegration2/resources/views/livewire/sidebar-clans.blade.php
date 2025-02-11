<div class="conteneurCanaux">
    <!-- Global Option -->
    <div class="canal" data-clan="global" wire:click="selectClan('global')">
        <a href="#">
            <div><i class="fa-solid fa-hashtag"></i> global</div>
        </a>
        <div class="iconesModificationCanal">
            <a href="#"><i class="fa-solid fa-pen"></i></a>
            <a href="#"><i class="fa-solid fa-x"></i></a>
        </div>
    </div>

    <!-- User's Clans -->
    @foreach($userClans as $clan)
        <div class="canal" data-clan="{{ $clan->clan_id }}" wire:click="selectClan('{{ $clan->clan_id }}')">
            <a href="#">
                <div><i class="fa-solid fa-hashtag"></i> {{ $clan->clan_nom }}</div>
            </a>
        </div>
    @endforeach
</div>
