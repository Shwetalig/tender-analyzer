<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tender Analytics</h2>
    </x-slot>

    <div class="p-6 space-y-6">
        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded p-4 shadow">
                <h3 class="text-sm font-medium text-gray-500">Total Tenders</h3>
                <p class="text-2xl font-bold">{{ $tagsCount->sum() }}</p>
            </div>
            <div class="bg-white rounded p-4 shadow">
                <h3 class="text-sm font-medium text-gray-500">Unique Tags</h3>
                <p class="text-2xl font-bold">{{ $tagsCount->count() }}</p>
            </div>
            <div class="bg-white rounded p-4 shadow">
                <h3 class="text-sm font-medium text-gray-500">Duplicates</h3>
                <p class="text-2xl font-bold text-red-600">{{ $duplicateCount }}</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-lg font-semibold mb-2">Tenders by Tag</h3>
                <canvas id="tagChart" height="200"></canvas>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2">Daily Uploads</h3>
                <canvas id="uploadChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const tagData = {
            labels: @json($tagsCount->keys()),
            datasets: [{
                label: 'Tenders',
                data: @json($tagsCount->values()),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
            }]
        };

        const uploadData = {
            labels: @json($dailyUploads->pluck('date')),
            datasets: [{
                label: 'Uploads',
                data: @json($dailyUploads->pluck('count')),
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
            }]
        };

        new Chart(document.getElementById('tagChart'), {
            type: 'bar',
            data: tagData,
            options: { responsive: true }
        });

        new Chart(document.getElementById('uploadChart'), {
            type: 'line',
            data: uploadData,
            options: { responsive: true }
        });
    </script>
</x-app-layout>
