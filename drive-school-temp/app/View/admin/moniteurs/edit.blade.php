<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier le Moniteur</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <form action="{{ route('admin.moniteurs.update', $moniteur) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold text-sm mb-1">Nom Complet</label>
                        <input type="text" name="name" value="{{ old('name', $moniteur->user->name) }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold text-sm mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $moniteur->user->email) }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold text-sm mb-1">Type de Permis</label>
                        <input type="text" name="type_permis" value="{{ old('type_permis', $moniteur->type_permis) }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="w-full bg-yellow-600 text-white py-2 rounded hover:bg-yellow-700 font-semibold shadow transition">Modifier</button>
                        <a href="{{ route('admin.moniteurs.index') }}" class="w-full text-center bg-gray-500 text-white py-2 rounded hover:bg-gray-600 font-semibold shadow transition">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
