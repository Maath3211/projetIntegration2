<div>
    <!-- Debug info -->
    <div class="debug-info">
        <p>Current Clan: {{ $selectedClanId }}</p>
        <p>Has Top Clans: {{ !empty($topClans) ? 'Yes' : 'No' }}</p>
        <p>Has Top Users: {{ !empty($topUsers) ? 'Yes' : 'No' }}</p>
    </div>

    @if($selectedClanId === 'global')
        <div wire:key="global-board">
            <livewire:global-leaderboard :topClans="$topClans" :topUsers="$topUsers" />
        </div>
    @else
        <div wire:key="clan-board-{{ $selectedClanId }}">
            <livewire:clan-leaderboard :selectedClanId="$selectedClanId" />
        </div>
    @endif
</div>
