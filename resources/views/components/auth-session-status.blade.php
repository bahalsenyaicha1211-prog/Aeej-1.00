@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'status-success']) }}>
        ✅ {{ $status }}
    </div>
@endif
