@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-3xl mx-auto bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Modifier la séance</h2>

        <form method="POST" action="{{ route('moniteur.seances.update', $seance) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Séance actuelle</label>
                <p class="mt-1 text-sm text-gray-600">{{ optional($seance->plageHoraire)->date }} {{ optional($seance->plageHoraire)->heure_debut }} - {{ optional($seance->plageHoraire)->heure_fin }}</p>
            </div>

            <div class="mb-4">
                <label for="plage_horaire_id" class="block text-sm font-medium text-gray-700">Nouveau créneau</label>
                <select id="plage_horaire_id" name="plage_horaire_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @foreach($availablePlages as $plage)
                        <option value="{{ $plage->id }}" {{ $plage->id === $seance->plage_horaire_id ? 'selected' : '' }}>
                            {{ $plage->date }} {{ $plage->heure_debut }} - {{ $plage->heure_fin }}
                            @if($plage->id !== $seance->plage_horaire_id) (disponible) @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('moniteur.dashboard') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50">Annuler</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
