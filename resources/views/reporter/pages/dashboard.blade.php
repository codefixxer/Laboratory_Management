@extends('reporter.layouts.app')

@section('content')
<div class="row mb-4">
  <div class="col-md-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center me-3"
               style="width:52px; height:52px; box-shadow:0 2px 8px rgba(0,0,0,.08);">
            <i class="fa-solid fa-vial-circle-check fs-3"></i>
          </div>
          <div>
            <h5 class="mb-1 fw-semibold text-success" style="letter-spacing:.5px;">Sampled Tests</h5>
            <h2 class="fw-bold mb-0">{{ number_format($sampledCount) }}</h2>
          </div>
        </div>
        <small class="text-muted">Tests ready for report writing</small>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="bg-danger text-white rounded-circle d-flex justify-content-center align-items-center me-3"
               style="width:52px; height:52px; box-shadow:0 2px 8px rgba(0,0,0,.08);">
            <i class="fa-solid fa-xmark-circle fs-3"></i>
          </div>
          <div>
            <h5 class="mb-1 fw-semibold text-danger" style="letter-spacing:.5px;">Revoked Tests</h5>
            <h2 class="fw-bold mb-0">{{ number_format($revokedCount) }}</h2>
          </div>
        </div>
        <small class="text-muted">Tests rejected/revoked</small>
      </div>
    </div>
  </div>
</div>


<div class="row mt-4">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        <h5 class="mb-0">Sampled vs Revoked (Last 30 days)</h5>
      </div>
      <div class="card-body">
        {!! $trendChart->container() !!}
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  {!! $trendChart->script() !!}
@endpush
