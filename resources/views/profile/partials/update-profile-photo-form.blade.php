@php
    $user = auth()->user();
    $initials = strtoupper(substr($user->name, 0, 1) . (explode(' ', $user->name)[1][0] ?? ''));
@endphp

<div class="avatar-display-container">
    <div class="initials-circle">
        {{ $initials }}
    </div>
    <p class="initials-label">Identifiant utilisateur</p>
</div>