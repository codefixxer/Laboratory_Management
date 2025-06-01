@extends('patient.layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 mb-5">
                <div class="card-header bg-gradient-primary text-white rounded-top-4"
                    style="background: linear-gradient(90deg, #007bff 60%, #67c7ff 100%);">
                    <h2 class="mb-0 text-center" style="font-weight:700; letter-spacing:1px;">
                        Completed Medical Reports
                    </h2>
                </div>
                <div class="card-body px-4 py-4">
                    @if($completedTests->isEmpty())
                        <div class="alert alert-info text-center mb-0 py-5">
                            <i class="fa fa-info-circle me-2"></i>
                            No completed reports available.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle shadow-sm rounded-3">
                                <thead class="table-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>Patient Name</th>
                                        <th>Test Name</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($completedTests as $i => $test)
                                    @php
                                        // Calculate total pending for this customer (assuming you have payments loaded)
                                        $totalPending = $test->customer->payments->sum('pending');
                                    @endphp
                                    <tr>
                                        <td class="fw-bold text-secondary">{{ $i+1 }}</td>
                                        <td>{{ $test->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $test->test->testName ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-success fs-6 px-3 py-2">Accepted</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($test->created_at)->format('d M, Y') }}</td>
                                        <td>
                                            @if($totalPending > 0)
                                                <span class="badge bg-warning text-dark fs-6 px-3 py-2 d-inline-flex align-items-center" style="font-size:1rem;">
                                                    <i class="fa fa-money-bill-wave me-1"></i>
                                                    Pay Pending (Rs. {{ $totalPending }})
                                                </span>
                                            @else
                                                <a href="{{ route('patient.report.view', [
                                                    'customerId' => $test->customerId,
                                                    'testId' => $test->addTestId
                                                ]) }}" class="btn btn-primary btn-sm shadow">
                                                    <i class="fa fa-eye"></i> View Report
                                                </a>
                                            @endif
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
</div>
@endsection
