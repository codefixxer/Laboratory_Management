@extends('patient.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center text-primary mb-4">Completed Reports</h2>

    @if($completedReports->isEmpty())
        <div class="alert alert-info text-center">
            No completed reports available.
        </div>
    @else
        <div class="card shadow-sm p-3">
            <h4 class="text-secondary">Patient Details</h4>
            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Name:</strong> {{ $completedReports->first()->customerTest->customer->name ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $completedReports->first()->customerTest->customer->email ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Phone:</strong> {{ $completedReports->first()->customerTest->customer->phone ?? 'N/A' }}</li>
            </ul>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Test Name</th>
                        <th>Sign Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($completedReports as $report)
                    <tr>
                        <td>{{ $report->customerTest->test->testName ?? 'N/A' }}</td>
                        <td>{{ ucfirst($report->signStatus) }}</td>
                        <td>
                            <a href="{{ route('patient.report.view', [
                                'customerId' => $report->customerTest->customer->customerId ?? null, 
                                'testId' => $report->customerTest->test->addTestId ?? null
                            ]) }}" class="btn btn-primary">
                                View Report
                            </a>
                            
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
                
                
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
