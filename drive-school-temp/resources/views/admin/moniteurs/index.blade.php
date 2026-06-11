<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestion des Moniteurs</h2>
            <a href="{{ route('admin.moniteurs.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Ajouter un Moniteur</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-3">Nom</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Type Permis</th>
                            <th class="p-3">Statut</th>
                            <th class="p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($moniteurs as $moniteur)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $moniteur->user->name }}</td>
                            <td class="p-3">{{ $moniteur->user->email }}</td>
                            <td class="p-3"><span class="bg-gray-200 px-2 py-1 rounded text-sm font-semibold">{{ $moniteur->type_permis }}</span></td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $moniteur->actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $moniteur->actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="p-3 flex space-x-2">
                                <a href="{{ route('admin.moniteurs.edit', $moniteur) }}" class="text-yellow-600 hover:text-yellow-900">Modifier</a>

                                <form action="{{ route('admin.moniteurs.toggle', $moniteur) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $moniteur->actif ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                        {{ $moniteur->actif ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
