@extends('layouts.public')

@section('title', 'Activités - AEEJ')

@section('content')
    <div style="padding: 100px 20px;">
        <h1>Nos Activités</h1>
        @if(isset($activites) && $activites->count() > 0)
            <div class="activites-list">
                @foreach($activites as $activite)
                    <div class="activite-card">
                        <h3>{{ $activite->nom }}</h3>
                        <p>{{ $activite->description ?? 'Aucune description disponible' }}</p>
                        @if($activite->date)
                            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($activite->date)->format('d/m/Y') }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p>Aucune activité disponible pour le moment.</p>
        @endif
    </div>
@endsection

