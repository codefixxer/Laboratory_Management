{{-- resources/views/receptionist/pages/debit/create.blade.php --}}
@extends('receptionist.layouts.app')

@section('content')
<div class="container my-5">
  <div class="card shadow-sm rounded-4">
    <div class="card-header bg-dark text-white">
      <h3 class="mb-0">Add New Debit</h3>
    </div>
    <div class="card-body p-4">
      <form action="{{ route('debit.store') }}" method="POST" novalidate>
        @csrf

        <div class="mb-3">
          <label for="debitAmount" class="form-label fw-semibold">Amount (₹)</label>
          <input
            type="number"
            id="debitAmount"
            name="debitAmount"
            class="form-control form-control-lg"
            placeholder="Enter amount"
            min="0"
            step="0.01"
            required
          >
        </div>

        <div class="mb-3">
          <label for="debitDetail" class="form-label fw-semibold">Detail</label>
          <textarea
            id="debitDetail"
            name="debitDetail"
            class="form-control form-control-lg"
            rows="3"
            placeholder="Enter description"
            required
          ></textarea>
        </div>

        <div class="mb-3">
          <label for="created_at" class="form-label fw-semibold">Created Date</label>
          <input
            type="date"
            id="created_at"
            name="created_at"
            class="form-control form-control-lg"
            required
          >
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary btn-lg px-4">
            <i class="bi bi-arrow-down-circle me-2"></i> Save Debit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
  .card-header h3 {
    font-weight: 600;
  }
  .form-control-lg {
    border-radius: 0.4rem;
  }
  .btn-lg {
    border-radius: 0.4rem;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush

