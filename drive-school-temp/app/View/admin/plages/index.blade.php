<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Configuration des Plages Horaires</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 h-fit">
                    <h3 class="font-bold text-lg text-gray-700 mb-4 border-b pb-2">Ajouter un créneau</h3>
                    <form action="{{ route('admin.plages.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-semibold mb-1">Moniteur</label>
                            <select name="moniteur_id" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">Choisir un moniteur...</option>
                                @foreach($moniteurs as $moniteur)
                                    <option value="{{ $moniteur->id }}">{{ $moniteur->user->name }} ({{ $moniteur->type_permis }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-semibold mb-1">Jour de la semaine</label>
                            <select name="jour_semaine" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="Lundi">Lundi</option>
                                <option value="Mardi">Mardi</option>
                                <option value="Mercredi">Mercredi</option>
                                <option value="Jeudi">Jeudi</option>
                                <option value="Vendredi">Vendredi</option>
                                <option value="Samedi">Samedi</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-1">Heure Début</label>
                                <input type="time" name="heure_debut" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-1">Heure Fin</label>
                                <input type="time" name="heure_fin" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-semibold shadow transition">
                            Ouvrir le créneau
                        </button>
                    </form>
                </div>

                <!-- 📋 جدول عرض التخطيط الحالي -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 md:col-span-2">
                    <h3 class="font-bold text-lg text-gray-700 mb-4 border-b pb-2">Planning des disponibilités</h3>

                    @if($plages->isEmpty())
                        <p class="text-gray-500 text-sm py-4">Aucune plage horaire configurée pour le moment.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b bg-gray-50 text-gray-600 text-sm font-semibold">
                                        <th class="p-3">Moniteur</th>
                                        <th class="p-3">Jour</th>
                                        <th class="p-3">Créneau Horaire</th>
                                        <th class="p-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700 text-sm">
                                    @foreach($plages as $plage)
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="p-3 font-medium">{{ $plage->moniteur->user->name }}</td>
                                        <td class="p-3">
                                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold">{{ $plage->jour_semaine }}</span>
                                        </td>
                                        <td class="p-3 font-mono text-gray-600">
                                            {{ \Carbon\Carbon::parse($plage->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($plage->heure_fin)->format('H:i') }}
                                        </td>
                                        <td class="p-3 text-center">
                                            <form action="{{ route('admin.plages.destroy', $plage) }}" method="POST" onsubmit="return confirm('Retirer ce créneau de la liste ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold transition">Retirer</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <?php
    use App\Http\Controllers\Candidat\ReservationController;

    Route::middleware(['auth','role:candidat'])
        ->prefix('candidat')
        ->group(function () {
            Route::get('/reservations', [ReservationController::class, 'index'])->name('candidat.reservations.index');
            Route::get('/reservations/events', [ReservationController::class, 'events'])->name('candidat.reservations.events');
            Route::post('/reservations/book', [ReservationController::class, 'book'])->name('candidat.reservations.book');
        });
    </x-app-layout>
