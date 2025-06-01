{{-- resources/views/receptionist/pages/dashboard.blade.php --}}
@extends('receptionist.layouts.app')

@section('content')
  {{-- (for debugging, you can uncomment this) --}}
  {{-- <pre>User ID: {{ $userId }}</pre> --}}

  <div class="row mb-4">
    {{-- 1) Sales Overview --}}
    <div class="col-md-7">
      <div class="card shadow-sm h-100">
        <div class="card-header">
          <h5 class="mb-0">My Sales Overview</h5>
        </div>
        <div class="card-body">
          {!! $salesOverviewChart->container() !!}
        </div>
      </div>
    </div>

  {{-- 2) Metrics Boxes --}}
<div class="col-md-5">
  <div class="row g-3">
    @foreach([
      ['Customers',      $totalCustomers,     'primary',   'fa-users'],
      ['Customer Tests', $totalCustomerTests, 'success',   'fa-clipboard-check'],
      ['Payments',       $totalPayments,      'info',      'fa-money-bill-wave'],
      ['Debits',         $totalDebits,        'warning',   'fa-arrow-down'],
      ['Credits',        $totalCredits,       'danger',    'fa-arrow-up'],
      ['Stock Entries',  $totalStocks,        'secondary', 'fa-boxes-stacked']
    ] as [$label, $value, $color, $icon])
      <div class="col-6">
        <div class="card shadow-sm border-0 position-relative overflow-hidden h-100">
          <div class="position-absolute top-0 start-0 w-100 h-100"
               style="background: rgba(var(--bs-{{ $color }}-rgb), .05);"></div>
          <div class="card-body position-relative">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-{{ $color }} text-white rounded-circle d-flex justify-content-center align-items-center me-3"
                   style="width:52px; height:52px; box-shadow:0 2px 8px rgba(0,0,0,.07);">
                <i class="fa-solid {{ $icon }} fs-3"></i>
              </div>
              <div>
                <h6 class="text-uppercase text-{{ $color }} mb-1" style="letter-spacing:.8px;">{{ $label }}</h6>
                <h2 class="fw-bold mb-0">{{ number_format($value) }}</h2>
              </div>
            </div>
            <small class="text-muted d-block text-end">Last 30 days</small>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

  </div>

  {{-- 3) Breakdown Charts --}}
  <div class="row g-4 mt-5">
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card shadow-sm h-100">
        <div class="card-header">Sales Breakdown</div>
        <div class="card-body">
          {!! $salesBreakdownChart->container() !!}
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="card shadow-sm h-100">
        <div class="card-header">Debits vs Credits</div>
        <div class="card-body">
          {!! $debitCreditChart->container() !!}
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="card shadow-sm h-100">
        <div class="card-header">Expense Breakdown</div>
        <div class="card-body">
          {!! $expenseChart->container() !!}
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="card shadow-sm h-100">
        <div class="card-header">Inventory Composition</div>
        <div class="card-body">
          {!! $inventoryChart->container() !!}
        </div>
      </div>
    </div>
  </div>

  {{-- 4) Additional Charts --}}
  <div class="row gy-4 mt-5">
    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-light"><h5 class="mb-0">Daily Sales (Last 30 days)</h5></div>
        <div class="card-body">
          {!! $dailySalesAreaChart->container() !!}
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-light"><h5 class="mb-0">Tests by Category</h5></div>
        <div class="card-body">
          {!! $testCategoryPieChart->container() !!}
        </div>
      </div>
    </div>
  </div>

  <div class="row gy-4 mt-4">
    <div class="col-12">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-light"><h5 class="mb-0">Monthly Debits vs Credits</h5></div>
        <div class="card-body">
          {!! $debitCreditAreaChart->container() !!}
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  {!! $salesOverviewChart->script() !!}
  {!! $salesBreakdownChart->script() !!}
  {!! $debitCreditChart->script() !!}
  {!! $expenseChart->script() !!}
  {!! $inventoryChart->script() !!}
  {!! $dailySalesAreaChart->script() !!}
  {!! $testCategoryPieChart->script() !!}
  {!! $debitCreditAreaChart->script() !!}
@endpush
