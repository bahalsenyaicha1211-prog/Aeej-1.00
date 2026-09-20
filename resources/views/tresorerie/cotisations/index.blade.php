<x-member-layout>
    <x-slot name="header">Cotisations</x-slot>

    @php $user = auth()->user(); @endphp

    @if(session('success'))
        <div class="alert alert--success" style="margin-bottom:16px;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert--danger" style="margin-bottom:16px;">
            @foreach($errors->all() as $error)
                <div>⚠️ {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="cotTabs" role="tablist" style="margin-bottom:16px;">
        <a class="cotTabs__btn {{ $tab === 'annuelle' ? 'is-active' : '' }}" href="{{ route('tresorerie.cotisations.index', ['tab' => 'annuelle']) }}">Cotisation annuelle</a>
        <a class="cotTabs__btn {{ $tab === 'volontaire' ? 'is-active' : '' }}" href="{{ route('tresorerie.cotisations.index', ['tab' => 'volontaire']) }}">Cotisations volontaires</a>
    </div>

    <div id="cotisations-results">
        @include('tresorerie.cotisations._results')
    </div>

<style>
.cotTabs{ display:flex; gap:8px; border-bottom:1px solid var(--border); }
.cotTabs__btn{
    display:inline-block;
    padding:10px 16px; border:0; background:none; cursor:pointer;
    font-size:14px; font-weight:800; color:var(--muted); text-decoration:none;
    border-bottom:2px solid transparent; margin-bottom:-1px;
    transition: color .15s ease, border-color .15s ease;
}
.cotTabs__btn:hover{ color:var(--text); }
.cotTabs__btn.is-active{ color:var(--brand2); border-color:var(--brand2); }
</style>
</x-member-layout>
