<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajouter un Moniteur</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <form action="{{ route('admin.moniteurs.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold text-sm mb-1">Nom Complet</label>
                        <input type="text" name="name" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold text-sm mb-1">Email</label>
                        <input type="email" name="email" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold text-sm mb-1">Type de Permis (Ex: B, C, D)</label>
                        <input type="text" name="type_permis" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold text-sm mb-1">Mot de passe</label>
                        <input type="password" name="password" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold text-sm mb-1">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-semibold shadow transition">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
