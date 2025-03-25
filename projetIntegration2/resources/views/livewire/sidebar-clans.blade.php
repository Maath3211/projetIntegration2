<div class="conteneurCanaux">
    <!-- Global Option -->
    <div class="canal {{ $selectedClanId === 'global' ? 'active' : '' }}" data-clan="global" wire:click="selectClan('global')">
        <a href="#">
            <div><i class="fa-solid fa-hashtag"></i> global</div>
        </a>
    </div>

    <!-- User's Clans -->
    @foreach($userClans as $clan)
    <div class="canal {{ $selectedClanId == $clan->clan_id ? 'active' : '' }}" 
         data-clan="{{ $clan->clan_id }}" 
         wire:click="selectClan('{{ $clan->clan_id }}')">
        <a href="#">
            <div>
                <i class="fa-solid fa-hashtag"></i> 
                {{ $clan->clan_nom }}
            </div>
        </a>
    </div>
    @endforeach
</div>