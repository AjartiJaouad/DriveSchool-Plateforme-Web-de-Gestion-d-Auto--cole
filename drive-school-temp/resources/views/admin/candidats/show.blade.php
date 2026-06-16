<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Détail du Candidat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Informations personnelles</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                            <div>
                                <p class="font-semibold">Nom</p>
                                <p>{{ $candidat->user->name }}</p>
                            </div>
                            <div>
                                <p class="font-semibold">Email</p>
                                <p>{{ $candidat->user->email }}</p>
                            </div>
                            <div>
                                <p class="font-semibold">Type de permis</p>
                                <p>{{ $candidat->type_permis ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold">Statut</p>
                                <p>{{ $candidat->statut ?? 'En cours' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">Progression du dossier</h3>
                        <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700">
                            <p><span class="font-semibold">Date inscription :</span> {{ optional($candidat->progressionDossier)->created_at?->format('d/m/Y') ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Heures prévues :</span> {{ optional($candidat->progressionDossier)->total_heures_prevues ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Heures réalisées :</span> {{ optional($candidat->progressionDossier)->total_heures_realisees ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Progression :</span> {{ optional($candidat->progressionDossier)->pourcentage_progres ?? 0 }}%</p>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.candidats.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Retour à la liste</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
