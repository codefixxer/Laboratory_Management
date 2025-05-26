{{-- resources/views/receptionist/pages/reports/invoice.blade.php --}}
@extends('receptionist.layouts.app')

@section('content')
<div class="container my-5 invoice-container">

  {{-- Header --}}
  <div class="row align-items-center mb-4">
    <div class="col-8">
      <h1 class="display-4 text-primary">Invoice</h1>
      <p class="mb-1">Invoice #: <strong>INV-{{ $customer->customerId }}</strong></p>
      <p>Date: <strong>{{ now()->format('Y-m-d') }}</strong></p>
    </div>
    <div class="col-4 text-end">
      {{-- Print button --}}
      <button onclick="window.print()" class="btn btn-outline-primary mb-2 d-print-none">
        <i class="bi bi-printer"></i> Print Invoice
      </button>
      <div>
        <img src="/images/logo.png" alt="Lab Logo" style="height:80px;">
      </div>
    </div>
  </div>

  {{-- Status Badge --}}
  <div class="mb-4 text-end">
    @if($customer->payment->pending == 0)
      <span class="badge bg-success fs-5">PAID</span>
    @else
      <span class="badge bg-warning fs-5">DUE</span>
    @endif
  </div>

  {{-- Patient Details --}}
  <div class="card mb-4 shadow-sm border-primary">
    <div class="card-body">
      <h5 class="card-title text-primary">Patient Details</h5>
      <p><strong>Name:</strong> {{ $customer->title }} {{ $customer->name }}</p>
      <p><strong>Username:</strong> {{ $customer->user_name }}</p>
      <p><strong>Phone:</strong> +92{{ $customer->phone }}</p>
      <p><strong>Email:</strong> {{ $customer->email ?? '—' }}</p>
      <p><strong>Relation:</strong> {{ $customer->relation }}</p>
    </div>
  </div>

  {{-- Selected Tests --}}
  <div class="card mb-4 shadow-sm border-info">
    <div class="card-body">
      <h5 class="card-title text-info">Selected Tests</h5>
      <table class="table table-striped mb-0">
        <thead class="table-dark">
          <tr>
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
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $t->testName }}</td>
              <td>{{ $cat }}</td>
              <td class="text-end">{{ number_format($cost,2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- Referral / Panel --}}
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card shadow-sm p-3 border-secondary">
        <h6 class="text-secondary">Referral / Panel</h6>
        @if($customer->staffPanel)
          <p>Type: <strong>Staff Panel</strong></p>
          <p>By: <strong>{{ $customer->staffPanel->user->name }}</strong></p>
        @elseif($customer->externalPanel)
          <p>Type: <strong>External Panel</strong></p>
          <p>Panel: <strong>{{ $customer->externalPanel->panelName }}</strong></p>
        @elseif($customer->referral)
          <p>Referrer: <strong>{{ $customer->referral->referrerName }}</strong></p>
        @else
          <p><em>None</em></p>
        @endif
      </div>
    </div>
    {{-- Billing Summary --}}
    <div class="col-md-6">
      <div class="card shadow-sm p-3 border-dark">
        <h6 class="text-dark">Billing Summary</h6>
        <table class="table border-0 mb-0">
          <tr>
            <th>Subtotal:</th>
            <td class="text-end">₹{{ number_format($subtotal,2) }}</td>
          </tr>
          <tr>
            <th>Discount:</th>
            <td class="text-end">
              ₹{{ number_format($subtotal - ($customer->payment->recieved + $customer->payment->pending),2) }}
            </td>
          </tr>
          <tr>
            <th>Paid:</th>
            <td class="text-end text-success">₹{{ number_format($customer->payment->recieved,2) }}</td>
          </tr>
          <tr>
            <th>Due:</th>
            <td class="text-end text-danger">₹{{ number_format($customer->payment->pending,2) }}</td>
          </tr>
          <tr class="table-active">
            <th>Total:</th>
            <th class="text-end">₹{{ number_format($subtotal,2) }}</th>
          </tr>
        </table>
      </div>
    </div>
  </div>

  {{-- Signature --}}
  <div class="row mt-5">
    <div class="col-6">
      <p>__________________________</p>
      <p><strong>Authorized Signatory</strong></p>
    </div>
    <div class="col-6 text-end">
      <p>__________________________</p>
      <p><strong>Patient Signature</strong></p>
    </div>
  </div>

  {{-- Footer --}}
  <div class="text-center text-mu.ted mt-4">
    <p>Thank you for choosing <strong>Medicore Lab</strong>!</p>
    <small>123 Lab Street, Karachi • +92-300-1234567 • info@handsomelab.com</small>
  </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
  .invoice-container {
    border: 2px solid #dee2e6;
    padding: 30px;
    border-radius: 10px;
    background: #fff;
  }
  table.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f8f9fa;
  }
  /* Print-specific rules */
  @media print {
    .d-print-none { display: none !important; }
    .invoice-container { box-shadow: none; border: none; }
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
