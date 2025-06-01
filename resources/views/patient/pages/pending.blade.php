@extends('patient.layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 mb-5">
                <div class="card-header bg-gradient-warning text-dark rounded-top-4" style="background: linear-gradient(90deg, #f9d423 60%, #ff4e50 100%);">
                    <h2 class="mb-0 text-center" style="font-weight:700; letter-spacing:1px;">Pending Medical Reports</h2>
                </div>
                <div class="card-body px-4 py-4">
                    @if($pendingTests->isEmpty())
                        <div class="alert alert-info text-center mb-0 py-5">
                            <i class="fa fa-hourglass-half me-2"></i>
                            No pending reports found.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle shadow-sm rounded-3">
                                <thead class="table-warning">
                                    <tr>
                                        <th>#</th>
                                        <th>Test Name</th>
                                        <th>Sample Type</th>
                                        <th>Requested Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingTests as $i => $test)
                                        <tr>
                                            <td class="fw-bold text-secondary">{{ $i+1 }}</td>
                                            <td>{{ $test->test->testName ?? 'N/A' }}</td>
                                            <td>{{ $test->test->typeSample ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($test->created_at)->format('d M, Y') }}</td>
                                            <td>
                                                <span class="badge bg-warning text-dark fs-6 px-3 py-2 animate__animated animate__pulse animate__infinite">Pending</span>
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
