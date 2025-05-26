{{-- resources/views/admin/pages/reports/comparison.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container-fluid my-4">

  {{-- Title --}}
  <div class="row mb-3">
    <div class="col">
      <h2 class="fw-bold">Sales vs. Expenses</h2>
    </div>
  </div>

  {{-- Filters --}}
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <input
        type="text" id="searchInput"
        class="form-control"
        placeholder="Search customer, type, detail, date..."
      >
    </div>
    <div class="col-md-3">
      <input type="date" id="fromDate" class="form-control">
    </div>
    <div class="col-md-3">
      <input type="date" id="toDate" class="form-control">
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button id="resetBtn" class="btn btn-secondary w-100">Reset All</button>
    </div>
  </div>

  {{-- Summary Cards --}}
{{-- Improved Summary Cards --}}
<div class="row row-cols-1 row-cols-md-5 g-3 mb-4">
  {{-- Total Sales --}}
  <div class="col">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-body text-center py-4">
        <i class="bi bi-piggy-bank display-6 text-success mb-2"></i>
        <h6 class="text-uppercase text-muted mb-1">Total Sales</h6>
        <h3 id="totalSalesCard" class="fw-bold">₹{{ number_format($totalSales,2) }}</h3>
      </div>
    </div>
  </div>

  {{-- Total Debits --}}
  <div class="col">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-body text-center py-4">
        <i class="bi bi-cash-stack display-6 text-danger mb-2"></i>
        <h6 class="text-uppercase text-muted mb-1">Total Debits</h6>
        <h3 id="totalDebitsCard" class="fw-bold">₹{{ number_format($totalDebits,2) }}</h3>
      </div>
    </div>
  </div>

  {{-- Stock Cost --}}
  <div class="col">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-body text-center py-4">
        <i class="bi bi-box-seam display-6 text-warning mb-2"></i>
        <h6 class="text-uppercase text-muted mb-1">Stock Cost</h6>
        <h3 id="totalStockCard" class="fw-bold">₹{{ number_format($totalStockCost,2) }}</h3>
      </div>
    </div>
  </div>

  {{-- Total Credits --}}
  <div class="col">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-body text-center py-4">
        <i class="bi bi-bank2 display-6 text-info mb-2"></i>
        <h6 class="text-uppercase text-muted mb-1">Total Credits</h6>
        <h3 id="totalCreditsCard" class="fw-bold">₹{{ number_format($totalCredits,2) }}</h3>
      </div>
    </div>
  </div>

  {{-- Total Expense --}}
  <div class="col">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-body text-center py-4">
        <i class="bi bi-receipt display-6 text-secondary mb-2"></i>
        <h6 class="text-uppercase text-muted mb-1">Total Expense</h6>
        <h3 id="totalExpenseCard" class="fw-bold">₹{{ number_format($totalExpense,2) }}</h3>
      </div>
    </div>
  </div>
</div>

{{-- Full-Width Net Profit --}}
<div class="row mb-5">
  <div class="col-12">
    <div class="card bg-dark text-white shadow-lg border-0">
      <div class="card-body text-center py-5">
        <h5 class="text-uppercase text-light mb-3">Net Profit</h5>
        <h1 id="netProfitCard" class="display-1 fw-bold">
          ₹{{ number_format($net,2) }}
        </h1>
      </div>
    </div>
  </div>
</div>


  {{-- Side-by-Side Tables --}}
  <div class="row g-4">
    {{-- Sales --}}
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-light"><h5 class="mb-0">Sales</h5></div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0" id="salesTable">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Customer</th>
                <th class="text-end">Received</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($salesPaginator as $sale)
              <tr
                data-date="{{ $sale->created_at->toDateString() }}"
                data-search="{{ strtolower($sale->customer->name.' '.$sale->recieved) }}"
                data-amount="{{ $sale->recieved }}"
              >
                <td>{{ $loop->iteration + ($salesPaginator->currentPage()-1)*$salesPaginator->perPage() }}</td>
                <td>{{ $sale->customer->name }}</td>
                <td class="text-end text-success">₹{{ number_format($sale->recieved,2) }}</td>
                <td>{{ $sale->created_at->format('Y-m-d') }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-footer">
          {{ $salesPaginator->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>

    {{-- Expenses --}}
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-light"><h5 class="mb-0">Expenses</h5></div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0" id="expensesTable">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Type</th>
                <th>Detail</th>
                <th class="text-end">Amount</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($expensesPaginator as $e)
              <tr
                data-date="{{ $e['date'] }}"
                data-search="{{ strtolower($e['type'].' '.$e['detail']) }}"
                data-amount="{{ $e['amount'] }}"
              >
                <td>{{ $loop->iteration + ($expensesPaginator->currentPage()-1)*$expensesPaginator->perPage() }}</td>
                <td>{{ $e['type'] }}</td>
                <td>{{ $e['detail'] }}</td>
                <td class="text-end {{ $e['amount']<0 ? 'text-primary' : 'text-danger' }}">
                  {{ $e['amount'] < 0
                     ? '-₹'.number_format(abs($e['amount']),2)
                     : '₹'.number_format($e['amount'],2) }}
                </td>
                <td>{{ $e['date'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-footer">
          {{ $expensesPaginator->links('pagination::bootstrap-5', ['pageName'=>'expenses_page']) }}
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
  const salesRows   = Array.from(document.querySelectorAll('#salesTable tbody tr'));
  const expenseRows = Array.from(document.querySelectorAll('#expensesTable tbody tr'));
  const fromInput   = document.getElementById('fromDate');
  const toInput     = document.getElementById('toDate');
  const searchInput = document.getElementById('searchInput');

  function applyComparisonFilters() {
    const from = fromInput.value;
    const to   = toInput.value;
    const term = searchInput.value.toLowerCase();

    let sumSales = 0, sumDebits = 0, sumStock = 0, sumCredits = 0;

    // Sales
    salesRows.forEach(row => {
      const date = row.dataset.date;
      const txt  = row.dataset.search;
      const amt  = parseFloat(row.dataset.amount);
      const inDate   = (!from || date >= from) && (!to || date <= to);
      const inSearch = !term || txt.includes(term);
      row.style.display = (inDate && inSearch) ? '' : 'none';
      if (inDate && inSearch) sumSales += amt;
    });

    // Expenses breakdown
    expenseRows.forEach(row => {
      const date = row.dataset.date;
      const txt  = row.dataset.search;
      const amt  = parseFloat(row.dataset.amount);
      const inDate   = (!from || date >= from) && (!to || date <= to);
      const inSearch = !term || txt.includes(term);
      row.style.display = (inDate && inSearch) ? '' : 'none';
      if (inDate && inSearch) {
        if (txt.startsWith('debit')) sumDebits += amt;
        else if (txt.startsWith('stock')) sumStock += amt;
        else if (txt.startsWith('credit')) sumCredits += amt;
      }
    });

    const totalExpense = sumDebits + sumStock - sumCredits;
    const net = sumSales - totalExpense;

    document.getElementById('totalSalesCard')  .innerText = '₹' + sumSales.toFixed(2);
    document.getElementById('totalDebitsCard') .innerText = '₹' + sumDebits.toFixed(2);
    document.getElementById('totalStockCard')  .innerText = '₹' + sumStock.toFixed(2);
    document.getElementById('totalCreditsCard').innerText = '₹' + sumCredits.toFixed(2);
    document.getElementById('totalExpenseCard').innerText = '₹' + totalExpense.toFixed(2);
    document.getElementById('netProfitCard')   .innerText = '₹' + net.toFixed(2);
  }

  fromInput.addEventListener('change', applyComparisonFilters);
  toInput  .addEventListener('change', applyComparisonFilters);
  searchInput.addEventListener('input', applyComparisonFilters);

  document.getElementById('resetBtn').addEventListener('click', () => {
    fromInput.value   = '';
    toInput.value     = '';
    searchInput.value = '';
    applyComparisonFilters();
  });

  // Initialize totals and filtering
  applyComparisonFilters();
</script>
@endpush
