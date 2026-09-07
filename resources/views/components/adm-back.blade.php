@props([
    'href',
    'label' => 'Retour',
])

<a {{ $attributes->merge(['class' => 'admBack']) }} href="{{ $href }}">
    <svg class="admBack__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M15 18l-6-6 6-6"/>
    </svg>
    <span>{{ $label }}</span>
</a>
