<div 
    id="clanInfo" 
    data-clan-name="{{ 
        $selectedClanId === 'global' 
            ? 'Global' 
            : ($selectedClan?->nom ?? 'Unknown') 
    }}">
    
    <div wire:key="leaderboard-{{ $selectedClanId }}-{{ $refreshKey }}">
        @if($selectedClanId === 'global')
            <livewire:global-leaderboard
                :topClans="$topClans"
                :topUsers="$topUsers"
                :wire:key="'global-' . $refreshKey" />
        @else
            <livewire:clan-leaderboard
                :selectedClanId="$selectedClanId"
                :wire:key="'clan-' . $selectedClanId . '-' . $refreshKey" />
        @endif
    </div>
</div>

@push('scripts')
<script wire:ignore>
    document.addEventListener('livewire:init', () => {
        Livewire.on('switchedClan', (data) => {
            console.log('Clan switched to:', data.clanId);
            updateDocumentTitle();
        });
    });

    function updateDocumentTitle() {
        const clanInfo = document.getElementById('clanInfo');
        if (clanInfo) {
            const clanName = clanInfo.getAttribute('data-clan-name');
            document.title = `Leaderboards - ${clanName}`;
            console.log("Document title updated to:", document.title);
        }
    }

    document.addEventListener('DOMContentLoaded', updateDocumentTitle);
</script>
@endpush