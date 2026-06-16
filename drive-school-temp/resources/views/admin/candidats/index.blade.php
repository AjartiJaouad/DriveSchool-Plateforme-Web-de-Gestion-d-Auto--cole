<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestion des Candidats</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 text-gray-600 text-sm font-semibold">
                                <th class="p-3">Nom</th>
                                <th class="p-3">Email</th>
                                <th class="p-3">Type de permis</th>
                                <th class="p-3">Statut dossier</th>
                                <th class="p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @forelse($candidats as $candidat)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 font-medium">{{ $candidat->user->name }}</td>
                                    <td class="p-3">{{ $candidat->user->email }}</td>
                                    <td class="p-3">{{ $candidat->type_permis ?? 'N/A' }}</td>
                                    <td class="p-3">{{ $candidat->statut ?? 'En cours' }}</td>
                                    <td class="p-3">
                                        <a href="{{ route('admin.candidats.show', $candidat) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Voir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-gray-500">Aucun candidat trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $candidats->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
