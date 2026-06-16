@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Mon Espace Candidat</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Section Mes Séances -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold text-lg border-b pb-2 mb-4">Mes Séances</h2>

            <h3 class="text-sm font-bold text-green-600">À venir :</h3>
            @if(isset($seancesAvenir) && $seancesAvenir->count())
                <ul class="mb-4">
                    @foreach($seancesAvenir as $seance)
                        <li class="mb-2 text-sm flex justify-between items-center">
                            <span>
                                Le {{ \Carbon\Carbon::parse($seance->plageHoraire->date . ' ' . $seance->plageHoraire->heure_debut)->format('d/m/Y à H:i') }}
                            </span>
                            <form action="{{ route('candidat.seance.updateStatut', $seance) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="action" value="annuler">
                                <button type="submit" class="text-red-500 hover:underline text-xs">Annuler</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">Aucune séance à venir pour le moment.</p>
            @endif
        </div>

        <!-- Section Calendrier -->
        <div class="lg:col-span-2 bg-white p-4 rounded shadow">
            <div id='calendar'></div>
        </div>
    </div>
</div>

<!-- Scripts FullCalendar -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'timeGridWeek',
      slotMinTime: '08:00:00',
      slotMaxTime: '20:00:00',
      events: "{{ route('candidat.seances.json') }}",

      dateClick: function(info) {
         if(confirm("Voulez-vous réserver une séance le " + info.dateStr + " ?")) {
             // Logique pour la réservation
         }
      }
    });
    calendar.render();
  });
</script>
@endsection
