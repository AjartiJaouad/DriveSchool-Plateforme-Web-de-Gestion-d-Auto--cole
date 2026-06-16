<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Choisir un créneau de conduite 🚗
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- 📅 مكان ظهور الأجندة الجرافيك -->
                <div id='calendar' class="bg-gray-50 p-4 rounded-lg shadow-inner"></div>

            </div>
        </div>
    </div>

    <!-- 🌟 إدخال مكتبة FullCalendar عن طريق الـ CDN -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek', // كيعرض السيمانة مقسمة بالسوايع (أحسن خيار للأوتو إيكول)
                slotMinTime: '08:00:00',     // بداية وقت العمل
                slotMaxTime: '20:00:00',     // نهاية وقت العمل
                locale: 'fr',                // اللغة الفرنسية
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
                },
                allDaySlot: false,          // حيد الخانة ديال "اليوم كاملا" حيت حصص السياقة بالوقت
                events: [
                    // هنا غادي نحطو السوايع الخاويين من بعد ف التيكيت 12، دابا حطينا مثال تجريبي
                    {
                        title: 'Créneau Disponible (Exemple)',
                        start: '2026-06-15T10:00:00',
                        end: '2026-06-15T12:00:00',
                        backgroundColor: '#3b82f6',
                        borderColor: '#3b82f6',
                    }
                ],
                eventClick: function(info) {
                    alert('Vous avez cliqué sur : ' + info.event.title);
                }
            });

            calendar.render();
        });
    </script>
</x-app-layout>
