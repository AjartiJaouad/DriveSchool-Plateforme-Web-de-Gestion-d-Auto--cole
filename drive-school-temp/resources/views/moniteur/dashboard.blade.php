<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon Tableau de Bord (Moniteur)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Mon Planning</h3>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Modal -->
    <div id="eventModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Détails de la séance</h3>
                <div class="mt-2 px-7 py-3 text-left text-sm text-gray-500">
                    <p id="modalCandidat" class="mb-2"></p>
                    <p id="modalStatut" class="mb-4"></p>
                    
                    <div class="flex justify-between space-x-2">
                        <form id="validateForm" method="POST" action="">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="statut" value="valide">
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600">Valider</button>
                        </form>

                        <form id="cancelForm" method="POST" action="">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="statut" value="annule">
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-600">Annuler</button>
                        </form>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 text-left">
                    <h4 class="font-medium text-gray-900 mb-2">Évaluation pédagogique</h4>
                    <form id="evaluationForm" method="POST" action="">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Note (/5)</label>
                            <input type="number" name="note_performance" min="1" max="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Remarques</label>
                            <textarea name="remarques" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">Enregistrer l'évaluation</button>
                    </form>
                </div>
                
                <div class="items-center px-4 py-3 mt-4 text-center">
                    <button id="closeModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded shadow hover:bg-gray-300">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var modal = document.getElementById('eventModal');
        var closeModal = document.getElementById('closeModal');
        
        closeModal.onclick = function() {
            modal.classList.add('hidden');
        }

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            locale: 'fr',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,timeGridDay'
            },
            events: '{{ route("moniteur.events") }}',
            eventClick: function(info) {
                var eventObj = info.event;
                var seanceId = eventObj.id;
                
                document.getElementById('modalTitle').innerText = eventObj.title;
                document.getElementById('modalCandidat').innerText = "Candidat: " + eventObj.extendedProps.candidat;
                document.getElementById('modalStatut').innerText = "Statut actuel: " + eventObj.extendedProps.statut;
                
                var updateUrl = "{{ url('moniteur/seances') }}/" + seanceId + "/statut";
                var evalUrl = "{{ url('moniteur/seances') }}/" + seanceId + "/evaluation";
                document.getElementById('validateForm').action = updateUrl;
                document.getElementById('cancelForm').action = updateUrl;
                document.getElementById('evaluationForm').action = evalUrl;
                
                modal.classList.remove('hidden');
            }
        });
        calendar.render();
    });
</script>
