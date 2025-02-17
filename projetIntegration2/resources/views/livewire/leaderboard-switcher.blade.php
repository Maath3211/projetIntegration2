<div>
    @if($selectedClanId === 'global')
    <livewire:global-leaderboard
        :topClans="$topClans"
        :topUsers="$topUsers"
        :key="'global-board'" />
    @else
    <livewire:clan-leaderboard
        :selectedClanId="$selectedClanId"
        :key="'clan-board-' . $selectedClanId" />
    @endif
</div>