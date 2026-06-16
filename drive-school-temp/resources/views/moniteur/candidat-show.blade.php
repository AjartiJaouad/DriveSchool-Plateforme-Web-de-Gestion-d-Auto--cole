@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-xl font-semibold mb-4">{{ $candidat->user->name }}</h2>
            <p class="text-sm text-gray-600">Inscrit le : {{ $candidat->created_at->format('d/m/Y') }}</p>
            <p class="mt-4 text-sm"><span class="font-semibold">Type permis :</span> {{ $candidat->type_permis ?? 'N/A' }}</p>
            <p class="mt-2 text-sm"><span class="font-semibold">Statut :</span> {{ $candidat->statut ?? 'En cours' }}</p>
            <p class="mt-4 text-lg font-bold">{{ $heuresEffectuees }}h</p>
            <p class="text-sm text-gray-500">Heures conduites validées</p>
        </div>

        <div class="lg:col-span-2 bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">Progression pédagogique</h3>
            @if($progression)
                <div class="grid gap-4 md:grid-cols-3 mb-6">
                    <div class="rounded-lg bg-blue-50 p-4">
                        <p class="text-sm text-blue-600 font-semibold">Heures prévues</p>
                        <p class="text-2xl font-bold">{{ $progression->total_heures_prevues }}h</p>
                    </div>
                    <div class="rounded-lg bg-green-50 p-4">
                        <p class="text-sm text-green-600 font-semibold">Heures réalisées</p>
                        <p class="text-2xl font-bold">{{ $progression->total_heures_realisees }}h</p>
                    </div>
                    <div class="rounded-lg bg-purple-50 p-4">
                        <p class="text-sm text-purple-600 font-semibold">Progression</p>
                        <p class="text-2xl font-bold">{{ $progression->pourcentage_progres }}%</p>
                    </div>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4 mb-6">
                    <h4 class="text-md font-semibold mb-2">Compétences évaluées</h4>
                    <p class="text-sm text-gray-700">Remarques : {{ $progression->statut_dossier ?? 'Aucune remarque enregistrée.' }}</p>
                </div>
            @else
                <p class="text-sm text-gray-500">Aucun bilan pédagogique initialisé.</p>
            @endif

            <div>
                <h3 class="text-lg font-semibold mb-4">Historique des leçons</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Heure</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Moniteur</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($seances as $seance)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ optional($seance->plageHoraire)->date }} {{ optional($seance->plageHoraire)->heure_debut }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $seance->plageHoraire->moniteur->user->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold
                                            {{ $seance->statut === 'valide' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $seance->statut === 'annule' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $seance->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                            {{ ucfirst($seance->statut) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-sm text-gray-500">Aucune séance enregistrée pour ce candidat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">Évaluations et remarques</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Séance</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compétences</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarques</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $evaluations = $seances->filter(fn($seance) => $seance->note_performance || $seance->remarques || !empty($seance->competences));
                            @endphp
                            @forelse($evaluations as $seance)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ optional($seance->plageHoraire)->date }} {{ optional($seance->plageHoraire)->heure_debut }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $seance->note_performance ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ is_array($seance->competences) ? implode(', ', $seance->competences) : ($seance->competences ?? 'Aucune') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $seance->remarques ?? 'Aucune' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-sm text-gray-500">Aucune évaluation disponible pour ce candidat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
