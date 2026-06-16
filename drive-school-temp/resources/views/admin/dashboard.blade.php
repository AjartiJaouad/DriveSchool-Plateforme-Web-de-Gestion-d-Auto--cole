<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Statistiques Globales</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg shadow">
                            <p class="text-sm text-blue-600 font-semibold">Candidats</p>
                            <p class="text-2xl font-bold">{{ $totalCandidats ?? 0 }}</p>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg shadow">
                            <p class="text-sm text-indigo-600 font-semibold">Moniteurs</p>
                            <p class="text-2xl font-bold">{{ $totalMoniteurs ?? 0 }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg shadow">
                            <p class="text-sm text-purple-600 font-semibold">Séances Totales</p>
                            <p class="text-2xl font-bold">{{ $totalSeances ?? 0 }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg shadow">
                            <p class="text-sm text-green-600 font-semibold">Taux de Réussite</p>
                            <p class="text-2xl font-bold">{{ $successRate ?? 0 }}%</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-medium mb-4">Évolution des Séances</h3>
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-100" style="height: 300px;">
                        <canvas id="seancesChart"></canvas>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('seancesChart');
        
        // Pass data from PHP
        const chartData = @json($chartData ?? ['labels' => [], 'data' => []]);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Nombre de séances',
                    data: chartData.data,
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
</script>
