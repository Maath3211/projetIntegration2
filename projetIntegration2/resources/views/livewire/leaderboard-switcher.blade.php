<div id="clanInfo" 
    data-clan-name="{{ 
        $selectedClanId === 'global' 
            ? 'Global' 
            : (App\Models\Clan::find($selectedClanId)?->nom ?? 'Unknown') 
    }}"
    wire:key="clan-info-{{ $selectedClanId }}">
    
    <div wire:key="content-{{ $selectedClanId }}">
        @if($selectedClanId === 'global')
            <livewire:global-leaderboard
                :topClans="$topClans"
                :topUsers="$topUsers"
                :key="'global'" />
        @else
            <livewire:clan-leaderboard
                :selectedClanId="$selectedClanId"
                :key="'clan-' . $selectedClanId" />
        @endif
    </div>
</div>

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script wire:ignore>
        function updateDocumentTitle() {
            const clanInfo = document.getElementById('clanInfo');
            if (clanInfo) {
                const clanName = clanInfo.getAttribute('data-clan-name');
                document.title = `Leaderboards - ${clanName}`;
                console.log("Document title updated to:", document.title);
            }
        }

        document.addEventListener('DOMContentLoaded', updateDocumentTitle);
        
        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.processed', (message, component) => {
                console.log('Livewire update:', component?.id, message);
                setTimeout(updateDocumentTitle, 100);
            });
        });
    </script>
@endsection