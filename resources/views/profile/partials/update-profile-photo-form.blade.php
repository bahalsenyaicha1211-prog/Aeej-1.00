@php
    $user = auth()->user();
    // Nettoyer les espaces superflus
    $name = trim($user->name);
    $parts = explode(' ', $name);
    
    $firstLetter = substr($parts[0], 0, 1);
    
    // On prend la première lettre du DERNIER mot s'il y en a plusieurs
    $lastLetter = (count($parts) > 1) 
        ? substr(end($parts), 0, 1) 
        : substr($name, 1, 1); // Si un seul mot, on prend la 2ème lettre

    $initials = strtoupper($firstLetter . $lastLetter);
@endphp

<div class="avatar-display-container">
    <div class="initials-circle">
        {{ $initials }}
    </div>
    <p class="initials-label">Identifiant utilisateur</p>
</div>