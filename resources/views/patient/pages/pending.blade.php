@extends('patient.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Pending Reports</h2>

    @if($pendingReports->isEmpty())
        <div class="alert alert-info">No pending reports found.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Test Name</th>
                    <th>Sample Type</th>
                    <th>Collected Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingReports as $report)
                    <tr>
                        <td>{{ $report->customerTest->test->testName ?? 'N/A' }}</td>
                        <td>{{ $report->customerTest->test->typeSample ?? 'N/A' }}</td>
                        <td>{{ $report->created_at->format('d M, Y') }}</td>
                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
