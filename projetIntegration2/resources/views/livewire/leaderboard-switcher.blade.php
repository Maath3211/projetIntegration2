<div id="clanInfo"
    data-clan-name="{{ $selectedClanId === 'global' ? 'Global' : optional(App\Models\Clan::find($selectedClanId))->nom }}"
    wire:key="clan-info-{{ $selectedClanId }}-{{ $refreshCounter }}">
    @if($selectedClanId === 'global')
        <livewire:global-leaderboard
            :topClans="$topClans"
            :topUsers="$topUsers"
            :key="'global-board-' . $refreshCounter" />
    @else
        <livewire:clan-leaderboard
            :selectedClanId="$selectedClanId"
            :key="'clan-board-' . $selectedClanId . '-' . $refreshCounter" />
    @endif
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
            } else {
                console.warn("clanInfo not found; skipping title update.");
            }
        }

        // Update title on initial DOM load.
        document.addEventListener('DOMContentLoaded', updateDocumentTitle);

        // Wait until Livewire is loaded, then attach hooks only if the element exists.
        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.processed', function (message, component) {
                setTimeout(updateDocumentTitle, 50);
            });
        });

        // Listen for Livewire updates to update the document title
        document.addEventListener('livewire:update', updateDocumentTitle);
    </script>
@endsection