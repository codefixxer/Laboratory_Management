@extends('sampler.layouts.app')

@section('content')
<div class="row mb-4">
  <div class="col-md-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="bg-warning text-white rounded-circle d-flex justify-content-center align-items-center me-3"
               style="width:52px; height:52px; box-shadow:0 2px 8px rgba(0,0,0,.08);">
            <i class="fa-solid fa-hourglass-half fs-3"></i>
          </div>
          <div>
            <h5 class="mb-1 fw-semibold text-warning" style="letter-spacing:.5px;">Pending Samples</h5>
            <h2 class="fw-bold mb-0">{{ number_format($pendingCount) }}</h2>
          </div>
        </div>
        <small class="text-muted">Samples to be collected</small>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center me-3"
               style="width:52px; height:52px; box-shadow:0 2px 8px rgba(0,0,0,.08);">
            <i class="fa-solid fa-check-circle fs-3"></i>
          </div>
          <div>
            <h5 class="mb-1 fw-semibold text-success" style="letter-spacing:.5px;">Collected Samples</h5>
            <h2 class="fw-bold mb-0">{{ number_format($collectedCount) }}</h2>
          </div>
        </div>
        <small class="text-muted">Total samples collected</small>
      </div>
    </div>
  </div>
</div>


<div class="row mt-4">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        <h5 class="mb-0">Collected Samples Trend</h5>
      </div>
      <div class="card-body">
        {!! $collectedLineChart->container() !!}
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  {!! $collectedLineChart->script() !!}
@endpush
