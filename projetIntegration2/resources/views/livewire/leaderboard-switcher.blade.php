<!-- Conteneur principal qui stocke l'information du clan actuel -->
<div 
    id="clanInfo" 
    data-clan-name="{{ 
        $selectedClanId === 'global' 
            ? 'Global' 
            : ($selectedClan?->nom ?? 'Inconnu') 
    }}">
    
    <!-- Use a more specific key pattern for the component -->
    <div>
        @if($selectedClanId === 'global')
            @livewire('global-leaderboard', 
                ['topClans' => $topClans, 'topUsers' => $topUsers], 
                key('global-leaderboard-' . $refreshKey)
            )
        @else
            @livewire('clan-leaderboard', 
                ['selectedClanId' => $selectedClanId], 
                key('clan-leaderboard-' . $selectedClanId . '-' . $refreshKey)
            )
        @endif
    </div>
</div>

<!-- Ajout de scripts JavaScript pour gérer les interactions et les mises à jour -->
@push('scripts')
<script wire:ignore>
    // Initialisation des écouteurs d'événements Livewire
    document.addEventListener('livewire:init', () => {
        // Écoute l'événement 'switchedClan' émis lorsqu'un utilisateur change de clan
        Livewire.on('switchedClan', (data) => {
            // Affiche dans la console l'ID du clan vers lequel on a basculé (pour débogage)
            console.log('Changement de clan vers:', data.clanId);
            // Met à jour le titre du document pour refléter le clan actuel
            updateDocumentTitle();
        });
    });

    /**
     * Fonction qui met à jour le titre du document avec le nom du clan sélectionné
     * Cela améliore l'expérience utilisateur en indiquant clairement le contexte actuel
     */
    function updateDocumentTitle() {
        // Récupère l'élément qui contient l'information du clan
        const clanInfo = document.getElementById('clanInfo');
        if (clanInfo) {
            // Extrait le nom du clan depuis l'attribut data-clan-name
            const clanName = clanInfo.getAttribute('data-clan-name');
            // Met à jour le titre de la page avec le nom du clan
            document.title = `Classements - ${clanName}`;
            // Affiche dans la console le nouveau titre (pour débogage)
            console.log("Titre du document mis à jour:", document.title);
        }
    }

    // Exécute la mise à jour du titre dès que le DOM est complètement chargé
    document.addEventListener('DOMContentLoaded', updateDocumentTitle);
</script>
@endpush