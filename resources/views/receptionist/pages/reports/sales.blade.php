{{-- resources/views/receptionist/pages/reports/sales.blade.php --}}
@extends('receptionist.layouts.app')

@section('content')
<div class="container-fluid my-4">

  {{-- Page Title --}}
  <div class="row mb-3">
    <div class="col">
      <h2 class="fw-bold">My Sale Report</h2>
    </div>
  </div>

  {{-- Filters --}}
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <label class="form-label">Search</label>
      <input type="text" id="searchInput" class="form-control" placeholder="Type ID, Name, or Date...">
    </div>
    <div class="col-md-3">
      <label class="form-label">From</label>
      <input type="date" id="fromDate" class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">To</label>
      <input type="date" id="toDate" class="form-control">
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <button id="resetBtn" class="btn btn-secondary w-100">Reset All</button>
    </div>
  </div>

  {{-- Summary Cards --}}
  <div class="row g-4 mb-5">
    <div class="col-md-4">
      <div class="card text-white bg-success h-100">
        <div class="card-body">
          <h5 class="card-title">Total Received</h5>
          <p class="display-6" id="totalReceived">₹{{ number_format($totalReceived, 2) }}</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-danger h-100">
        <div class="card-body">
          <h5 class="card-title">Total Pending</h5>
          <p class="display-6" id="totalPending">₹{{ number_format($totalPending, 2) }}</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-info h-100">
        <div class="card-body">
          <h5 class="card-title">Records Count</h5>
          <p class="display-6" id="recordCount">{{ $payments->count() }}</p>
        </div>
      </div>
    </div>
  </div>

  {{-- Monthly Breakdown --}}
  <div class="card mb-5 shadow-sm">
    <div class="card-header bg-light">
      <h5 class="mb-0">Monthly Breakdown</h5>
    </div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0" id="monthlyTable">
        <thead class="table-light">
          <tr>
            <th>Month</th>
            <th class="text-end">Received</th>
            <th class="text-end">Pending</th>
          </tr>
        </thead>
        <tbody>
          @foreach($monthly as $m)
          <tr data-date="{{ \Carbon\Carbon::parse($m->month.'-01')->toDateString() }}">
            <td>{{ $m->month }}</td>
            <td class="text-end text-success">₹{{ number_format($m->total_received, 2) }}</td>
            <td class="text-end text-danger">₹{{ number_format($m->total_pending, 2) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- Detailed Payments --}}
  <div class="card shadow-sm">
    <div class="card-header bg-light">
      <h5 class="mb-0">Detailed Payments</h5>
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0" id="paymentsTable">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th class="text-end">Received</th>
            <th class="text-end">Pending</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @foreach($payments as $payment)
          <tr
            data-date="{{ $payment->created_at->toDateString() }}"
            data-customer="{{ $payment->customerId }}"
            data-customer-name="{{ strtolower($payment->customer->name) }}"
            data-received="{{ $payment->recieved }}"
            data-pending="{{ $payment->pending }}"
          >
            <td>{{ $loop->iteration }}</td>
            <td>{{ $payment->customerId }}</td>
            <td>{{ $payment->customer->name }}</td>
            <td class="text-end text-success">₹{{ number_format($payment->recieved, 2) }}</td>
            <td class="text-end text-danger">₹{{ number_format($payment->pending, 2) }}</td>
            <td>{{ $payment->created_at->format('Y-m-d') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const rows        = Array.from(document.querySelectorAll('#paymentsTable tbody tr'));
  const fromInput   = document.getElementById('fromDate');
  const toInput     = document.getElementById('toDate');
  const searchInput = document.getElementById('searchInput');

  function applyFilters() {
    const from = fromInput.value;
    const to   = toInput.value;
    const term = searchInput.value.toLowerCase();

    let totalR = 0, totalP = 0, cnt = 0;

    rows.forEach(row => {
      const date    = row.dataset.date;
      const custId  = row.dataset.customer;
      const custName= row.dataset.customerName;

      const inDateRange = (!from || date >= from) && (!to || date <= to);
      const inSearch    = !term
        || custId.includes(term)
        || date.includes(term)
        || custName.includes(term);

      if (inDateRange && inSearch) {
        row.style.display = '';
        totalR += parseFloat(row.dataset.received);
        totalP += parseFloat(row.dataset.pending);
        cnt++;
      } else {
        row.style.display = 'none';
      }
    });

    document.getElementById('totalReceived').innerText = '₹' + totalR.toFixed(2);
    document.getElementById('totalPending' ).innerText = '₹' + totalP.toFixed(2);
    document.getElementById('recordCount'  ).innerText = cnt;
  }

  fromInput.addEventListener('change', applyFilters);
  toInput  .addEventListener('change', applyFilters);
  searchInput.addEventListener('input', applyFilters);

  document.getElementById('resetBtn').addEventListener('click', () => {
    fromInput.value = '';
    toInput.value   = '';
    searchInput.value = '';
    applyFilters();
  });

  // Initialize
  applyFilters();
</script>
@endpush
