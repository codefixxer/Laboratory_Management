@extends('patient.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Reports</h5>
                    <h2>{{ $totalReports }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title text-warning">Pending Reports</h5>
                    <h2>{{ $pendingReports }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title text-success">Accepted Reports</h5>
                    <h2>{{ $acceptedReports }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4">Reports Summary</h5>
            <canvas id="reportChart" style="max-height:350px;"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('reportChart').getContext('2d');
    const reportChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($graphData)) !!},
            datasets: [{
                label: 'Reports',
                data: {!! json_encode(array_values($graphData)) !!},
                backgroundColor: [
                    'rgba(30, 136, 229, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(76, 175, 80, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, precision:0 }
            }
        }
    });
</script>
@endsection
