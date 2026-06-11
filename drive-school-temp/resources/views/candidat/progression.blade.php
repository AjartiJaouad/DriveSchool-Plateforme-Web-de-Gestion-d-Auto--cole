<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon Livret de Progression') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Progression Actuelle</h3>
                    
                    @if($progression)
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-sm text-blue-600 font-semibold">Heures Prévues</p>
                                <p class="text-2xl font-bold">{{ $progression->total_heures_prevues }}h</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-sm text-green-600 font-semibold">Heures Réalisées</p>
                                <p class="text-2xl font-bold">{{ $progression->total_heures_realisees }}h</p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <p class="text-sm text-purple-600 font-semibold">Progression</p>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                    <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $progression->pourcentage_progres }}%"></div>
                                </div>
                                <p class="text-xs text-right mt-1">{{ $progression->pourcentage_progres }}%</p>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h4 class="font-medium text-md mb-4">Mes Séances</h4>
                            @if($progression->seances->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($progression->seances as $seance)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $seance->plageHoraire->date ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $seance->statut === 'valide' ? 'bg-green-100 text-green-800' : 
                                                       ($seance->statut === 'annule' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($seance->statut) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-gray-500 text-sm">Aucune séance pour le moment.</p>
                            @endif
                        </div>
                    @else
                        <p class="mt-4 text-gray-500">Aucun dossier de progression trouvé.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
