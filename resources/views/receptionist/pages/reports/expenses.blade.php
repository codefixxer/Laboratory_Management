{{-- resources/views/receptionist/pages/reports/expenses.blade.php --}}
@extends('receptionist.layouts.app')

@section('content')
<div class="container-fluid my-4">

  {{-- Title --}}
  <div class="row mb-3">
    <div class="col">
      <h2 class="fw-bold">My Expense Report</h2>
    </div>
  </div>

  {{-- Filters --}}
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <label class="form-label">Search</label>
      <input
        type="text"
        id="searchInput"
        class="form-control"
        placeholder="Search detail, item or date…"
      >
    </div>
    <div class="col-md-3">
      <label class="form-label">From</label>
      <input type="date" id="fromDate" class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">To</label>
      <input type="date" id="toDate" class="form-control">
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button id="resetBtn" class="btn btn-secondary w-100">Reset All</button>
    </div>
  </div>

  {{-- Summary Cards --}}
  <div class="row g-4 mb-5">
    <div class="col-md-4">
      <div class="card text-white bg-danger h-100 shadow-sm">
        <div class="card-body text-center py-4">
          <h6 class="text-uppercase mb-2">Total Debits</h6>
          <p class="display-6 mb-0" id="totalDebits">₹{{ number_format($totalDebits,2) }}</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-warning h-100 shadow-sm">
        <div class="card-body text-center py-4">
          <h6 class="text-uppercase mb-2">Stock Purchases</h6>
          <p class="display-6 mb-0" id="totalStockCost">₹{{ number_format($totalStockCost,2) }}</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-dark h-100 shadow-sm">
        <div class="card-body text-center py-4">
          <h6 class="text-uppercase mb-2">Total Expense</h6>
          <p class="display-6 mb-0" id="totalExpense">₹{{ number_format($totalExpense,2) }}</p>
        </div>
      </div>
    </div>
  </div>

  {{-- Tables Side by Side --}}
  <div class="row g-4">
    {{-- Debits --}}
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-light">
          <h5 class="mb-0">Debits</h5>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0" id="debitsTable">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Detail</th>
                <th class="text-end">Amount</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($debits as $d)
                <tr
                  data-date="{{ \Carbon\Carbon::parse($d->createdDate)->toDateString() }}"
                  data-search="{{ strtolower($d->debitDetail) }}"
                  data-amount="{{ $d->debitAmount }}"
                >
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $d->debitDetail }}</td>
                  <td class="text-end text-danger">₹{{ number_format($d->debitAmount,2) }}</td>
                  <td>{{ \Carbon\Carbon::parse($d->createdDate)->format('Y-m-d') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Stocks --}}
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-light">
          <h5 class="mb-0">Stock Purchases</h5>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0" id="stocksTable">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Item</th>
                <th>Detail</th>
                <th class="text-end">Qty × Price</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($stocks as $s)
                @php $cost = $s->itmQnt * $s->itmPrice; @endphp
                <tr
                  data-date="{{ \Carbon\Carbon::parse($s->createdDate)->toDateString() }}"
                  data-search="{{ strtolower($s->itemName.' '.$s->itemDetail) }}"
                  data-amount="{{ $cost }}"
                >
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $s->itemName }}</td>
                  <td>{{ $s->itemDetail }}</td>
                  <td class="text-end">₹{{ number_format($cost,2) }}</td>
                  <td>{{ \Carbon\Carbon::parse($s->createdDate)->format('Y-m-d') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@push('styles')
<link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
  rel="stylesheet"
/>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const debitRows = Array.from(document.querySelectorAll('#debitsTable tbody tr'));
  const stockRows = Array.from(document.querySelectorAll('#stocksTable tbody tr'));
  const fromInput = document.getElementById('fromDate');
  const toInput   = document.getElementById('toDate');
  const searchInput = document.getElementById('searchInput');

  function applyExpenseFilters() {
    const from = fromInput.value, to = toInput.value, term = searchInput.value.toLowerCase();
    let sumDebits = 0, sumStock = 0;

    debitRows.forEach(r => {
      const d = r.dataset.date, t = r.dataset.search, a = parseFloat(r.dataset.amount);
      const ok = (!from||d>=from)&&(!to||d<=to)&&(!term||t.includes(term));
      r.style.display = ok ? '' : 'none';
      if (ok) sumDebits += a;
    });

    stockRows.forEach(r => {
      const d = r.dataset.date, t = r.dataset.search, a = parseFloat(r.dataset.amount);
      const ok = (!from||d>=from)&&(!to||d<=to)&&(!term||t.includes(term));
      r.style.display = ok ? '' : 'none';
      if (ok) sumStock += a;
    });

    document.getElementById('totalDebits').innerText    = '₹' + sumDebits.toFixed(2);
    document.getElementById('totalStockCost').innerText = '₹' + sumStock.toFixed(2);
    document.getElementById('totalExpense').innerText   = '₹' + (sumDebits + sumStock).toFixed(2);
  }

  fromInput.addEventListener('change', applyExpenseFilters);
  toInput  .addEventListener('change', applyExpenseFilters);
  searchInput.addEventListener('input', applyExpenseFilters);
  document.getElementById('resetBtn').addEventListener('click', () => {
    fromInput.value = '';
    toInput.value   = '';
    searchInput.value = '';
    applyExpenseFilters();
  });

  applyExpenseFilters();
</script>
@endpush
