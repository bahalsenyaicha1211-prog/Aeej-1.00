@props([
    'user' => null,
    'name' => null,
    'photo' => null,
    'size' => 40,
    'bg' => '#055b20',
])

@php
    $displayName = $name ?? ($user?->name ?? '');
    $parts = array_values(array_filter(preg_split('/\s+/', trim($displayName))));
    $initials = mb_strtoupper(
        mb_substr($parts[0] ?? '', 0, 1)
        . (count($parts) > 1 ? mb_substr((string) end($parts), 0, 1) : '')
    );
    $initials = $initials !== '' ? $initials : '?';

    $path = $photo ?? $user?->profile_photo_path;
    $px = max(1, (int) $size);

    $src = null;
    if ($path) {
        $src = \Illuminate\Support\Str::startsWith($path, 'http')
            // URL Cloudinary : on insère une transformation de redimensionnement
            // (vignette légère, mise en cache par Cloudinary).
            ? preg_replace(
                '#/image/upload/#',
                '/image/upload/w_' . ($px * 2) . ',h_' . ($px * 2) . ',c_fill,g_auto,q_auto,f_auto/',
                $path,
                1
            )
            // Ancienne photo stockée en local.
            : asset('storage/' . ltrim($path, '/'));
    }
@endphp

@if ($src)
    <img src="{{ $src }}" alt="" loading="lazy"
         {{ $attributes->merge(['style' => "width:{$px}px;height:{$px}px;border-radius:50%;object-fit:cover;flex-shrink:0;display:block"]) }}>
@else
    <span aria-hidden="true"
          {{ $attributes->merge(['style' => "width:{$px}px;height:{$px}px;border-radius:50%;flex-shrink:0;display:inline-flex;align-items:center;justify-content:center;background:{$bg};color:#fff;font-weight:700;text-transform:uppercase;line-height:1;font-size:" . max(10, (int) round($px * 0.4)) . "px"]) }}>{{ $initials }}</span>
@endif
