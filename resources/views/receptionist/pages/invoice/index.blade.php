{{-- resources/views/receptionist/pages/reports/invoice.blade.php --}}
@extends('receptionist.layouts.app')

@section('content')
<div class="container my-5 invoice-container">

  {{-- Header --}}
  <div class="row align-items-center mb-5">
    <div class="col-8">
      <h1 class="display-4 text-primary fw-bold">Invoice</h1>
      <p class="mb-1"><strong>Invoice #:</strong> INV-{{ $customer->customerId }}</p>
      <p class="mb-1"><strong>Date:</strong> {{ now()->format('Y-m-d') }}</p>
      <p class="mt-2 text-muted fst-italic small">
        Login credentials: use <strong>Username &amp; Password</strong> 
      </p>
    </div>
    <div class="col-4 text-end">
      {{-- Print button --}}
      <button onclick="window.print()" class="btn btn-outline-primary mb-3 d-print-none">
        <i class="bi bi-printer me-1"></i> Print Invoice
      </button>
      <div>
        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="Lab Logo" class="img-fluid" style="max-height:80px;">
      </div>
    </div>
  </div>

  {{-- Status Badge --}}
  <div class="mb-4 text-end">
    @if($customer->payment->pending == 0)
      <span class="badge bg-success fs-6 py-2 px-3">PAID</span>
    @else
      <span class="badge bg-warning text-dark fs-6 py-2 px-3">DUE</span>
    @endif
  </div>

  {{-- Patient Details --}}
  <div class="card mb-4 shadow-sm border-primary rounded-3">
    <div class="card-header bg-primary text-white rounded-top-3">
      <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Patient Details</h5>
    </div>
    <div class="card-body">
      <div class="row gx-3">
        <div class="col-md-6">
          <p class="mb-2"><i class="bi bi-person-fill me-1 text-primary"></i><strong>Name:</strong> {{ $customer->title }} {{ $customer->name }}</p>
          <p class="mb-2"><i class="bi bi-person-badge-fill me-1 text-primary"></i><strong>Username:</strong> {{ $customer->user_name }}</p>
          <p class="mb-2"><i class="bi bi-lock-fill me-1 text-primary"></i><strong>Password:</strong> {{ $customer->password }}</p>
        </div>
        <div class="col-md-6">
          <p class="mb-2"><i class="bi bi-telephone-fill me-1 text-primary"></i><strong>Phone:</strong> +92{{ $customer->phone }}</p>
          <p class="mb-2"><i class="bi bi-envelope-fill me-1 text-primary"></i><strong>Email:</strong> {{ $customer->email ?? '—' }}</p>
          <p class="mb-2"><i class="bi bi-people-fill me-1 text-primary"></i><strong>Relation:</strong> {{ $customer->relation }}</p>
        </div>
      </div>
    </div>
  </div>

  {{-- Selected Tests --}}
  <div class="card mb-4 shadow-sm border-info rounded-3">
    <div class="card-header bg-info text-white rounded-top-3">
      <h5 class="mb-0"><i class="bi bi-clipboard2-check-fill me-2"></i>Selected Tests</h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-secondary">
            <tr class="text-center">
              <th>#</th>
              <th>Test Name</th>
              <th>Category</th>
              <th class="text-end">Cost (₹)</th>
            </tr>
          </thead>
          <tbody>
            @php $subtotal = 0; @endphp
            @foreach($customer->customerTests as $i => $ct)
              @php
                $t     = $ct->test;
                $cat   = $t->category->testCat ?? '—';
                $cost  = $t->testCost;
                $subtotal += $cost;
              @endphp
              <tr class="align-middle">
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $t->testName }}</td>
                <td>{{ $cat }}</td>
                <td class="text-end">{{ number_format($cost, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Referral / Panel & Billing Summary --}}
  <div class="row mb-4 g-4">
    <div class="col-md-6">
      <div class="card shadow-sm border-secondary rounded-3">
        <div class="card-header bg-secondary text-white rounded-top-3">
          <h6 class="mb-0"><i class="bi bi-people-fill me-2"></i>Referral / Panel</h6>
        </div>
        <div class="card-body">
          @if($customer->staffPanel)
            <p class="mb-2"><i class="bi bi-person-workspace me-1 text-secondary"></i><strong>Type:</strong> Staff Panel</p>
            <p><i class="bi bi-person-badge me-1 text-secondary"></i><strong>By:</strong> {{ $customer->staffPanel->user->name }}</p>
          @elseif($customer->externalPanel)
            <p class="mb-2"><i class="bi bi-building me-1 text-secondary"></i><strong>Type:</strong> External Panel</p>
            <p><i class="bi bi-card-list me-1 text-secondary"></i><strong>Panel:</strong> {{ $customer->externalPanel->panelName }}</p>
          @elseif($customer->referral)
            <p class="mb-2"><i class="bi bi-star-fill me-1 text-secondary"></i><strong>Referrer:</strong> {{ $customer->referral->referrerName }}</p>
          @else
            <p class="text-muted fst-italic mb-0">None</p>
          @endif
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm border-dark rounded-3">
        <div class="card-header bg-dark text-white rounded-top-3">
          <h6 class="mb-0"><i class="bi bi-receipt me-2"></i>Billing Summary</h6>
        </div>
        <div class="card-body p-0">
          <table class="table mb-0">
            <tr class="border-bottom">
              <th>Subtotal:</th>
              <td class="text-end">₹{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr class="border-bottom">
              <th>Discount:</th>
              <td class="text-end">₹{{ number_format($subtotal - ($customer->payment->recieved + $customer->payment->pending), 2) }}</td>
            </tr>
            <tr class="border-bottom">
              <th>Paid:</th>
              <td class="text-end text-success">₹{{ number_format($customer->payment->recieved, 2) }}</td>
            </tr>
            <tr class="border-bottom">
              <th>Due:</th>
              <td class="text-end text-danger">₹{{ number_format($customer->payment->pending, 2) }}</td>
            </tr>
            <tr class="table-active">
              <th>Total:</th>
              <th class="text-end">₹{{ number_format($subtotal, 2) }}</th>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Signature --}}
  <div class="row mt-5">
    <div class="col-6">
      <p class="mb-1 text-muted">__________________________</p>
      <p class="fw-semibold">Authorized Signatory</p>
    </div>
    <div class="col-6 text-end">
      <p class="mb-1 text-muted">__________________________</p>
      <p class="fw-semibold">Patient Signature</p>
    </div>
  </div>

  {{-- Footer --}}
  <div class="text-center text-muted mt-5">
    <p class="mb-1">Thank you for choosing <strong>Medicore Lab</strong>!</p>
    <small>123 Lab Street, Karachi • +92-300-1234567 • info@handsomelab.com</small>
  </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
  .invoice-container {
    background: #fdfdfd;
    border: 1px solid #e3e3e3;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  }
  .invoice-container h1,
  .invoice-container h5,
  .invoice-container h6 {
    letter-spacing: 0.5px;
  }
  .card-header {
    font-size: 1rem;
  }
  table.table-hover tbody tr:hover {
    background-color: #e9f7fe;
  }
  .table-secondary th {
    background-color: #cfe2ff !important;
  }
  @media print {
    .d-print-none {
      display: none !important;
    }
    .invoice-container {
      box-shadow: none;
      border: none;
    }
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
