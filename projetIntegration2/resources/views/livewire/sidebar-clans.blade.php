<div class="conteneurCanaux">
    <!-- Option Globale - Affiche le classement de tous les clans et utilisateurs -->
    <div class="canal {{ $selectedClanId === 'global' ? 'active' : '' }}" data-clan="global" wire:click="selectClan('global')">
        <a href="#">
            <!-- Utilisation de l'icône hashtag pour représenter un canal global -->
            <div><i class="fa-solid fa-hashtag"></i> global</div>
        </a>
    </div>

    <!-- Liste des clans de l'utilisateur - Affiche tous les clans auxquels l'utilisateur appartient -->
    @foreach($userClans as $clan)
    <!-- Élément de canal pour chaque clan avec mise en évidence du clan actuellement sélectionné -->
    <div class="canal {{ $selectedClanId == $clan->clan_id ? 'active' : '' }}" 
         data-clan="{{ $clan->clan_id }}" 
         wire:click="selectClan('{{ $clan->clan_id }}')">
        <a href="#">
            <!-- Affichage du nom du clan avec une icône hashtag comme préfixe -->
            <div><i class="fa-solid fa-hashtag"></i> {{ $clan->clan_nom }}</div>
        </a>
    </div>
    @endforeach
    
    <!-- Note: Si aucun clan n'est disponible pour cet utilisateur, seule l'option globale sera affichée -->
    
    <!-- Note: La classe 'active' est appliquée dynamiquement au canal sélectionné pour le mettre en évidence visuellement -->
    
    <!-- Note: L'événement wire:click déclenche la méthode selectClan dans le composant Livewire SidebarClans -->
    
    <!-- Note: L'attribut data-clan stocke l'identifiant du clan pour permettre des interactions JavaScript supplémentaires si nécessaire -->
</div>