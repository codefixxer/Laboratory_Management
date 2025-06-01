@extends('receptionist.layouts.app')

@section('content')
<div class="container my-5">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-gradient-primary text-dark rounded-top-4">
      <h3 class="mb-0"><i class="bi bi-box-seam me-2"></i>Add New Stock</h3>
    </div>
    <div class="card-body p-4">
      <form action="{{ route('stock.store') }}" method="POST" novalidate>
        @csrf

        <!-- Hidden User ID Field -->
        <input type="hidden" name="userId" value="{{ auth()->user()->id }}">

        <div class="row gx-3">
          <div class="col-md-6 mb-3">
            <label for="itemName" class="form-label">Item Name <span class="text-danger">*</span></label>
            <input type="text" id="itemName" name="itemName" class="form-control form-control-lg" placeholder="Enter item name" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="expDate" class="form-label">Expiry Date <span class="text-danger">*</span></label>
            <input type="date" id="expDate" name="expDate" class="form-control form-control-lg" required>
            <div class="invalid-feedback" id="expDateError">Expiry date must be in the future.</div>
          </div>
        </div>

        <div class="mb-3">
          <label for="itemDetail" class="form-label">Item Detail <span class="text-danger">*</span></label>
          <textarea id="itemDetail" name="itemDetail" class="form-control form-control-lg" rows="3" placeholder="Enter item details" required></textarea>
        </div>

        <div class="row gx-3">
          <div class="col-md-4 mb-3">
            <label for="itmQnt" class="form-label">Quantity <span class="text-danger">*</span></label>
            <input type="number" id="itmQnt" name="itmQnt" class="form-control form-control-lg" min="1" placeholder="0" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="itmPrice" class="form-label">Price Per Item (₹) <span class="text-danger">*</span></label>
            <input type="number" id="itmPrice" name="itmPrice" class="form-control form-control-lg" min="0" step="0.01" placeholder="0.00" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="totalPrice" class="form-label">Total Price (₹)</label>
            <input type="text" id="totalPrice" class="form-control form-control-lg bg-light" readonly placeholder="0.00">
          </div>
        </div>

        <div class="mb-3">
          <label for="created_at" class="form-label">Created Date <span class="text-danger">*</span></label>
          <input type="date" id="created_at" name="created_at" class="form-control form-control-lg" required>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-success btn-lg px-4">
            <i class="bi bi-save2 me-2"></i> Save Stock
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
  .card-header {
    background: linear-gradient(90deg, #007bff 60%, #67c7ff 100%);
  }
  .invalid-feedback {
    display: none;
  }
  input:invalid ~ .invalid-feedback,
  textarea:invalid ~ .invalid-feedback {
    display: block;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const expDateInput = document.getElementById('expDate');
    const expDateError = document.getElementById('expDateError');
    const today = new Date().toISOString().split('T')[0];
    expDateInput.setAttribute("min", today);

    expDateInput.addEventListener("change", function() {
      if (this.value <= today) {
        expDateInput.classList.add("is-invalid");
      } else {
        expDateInput.classList.remove("is-invalid");
      }
    });

    function updateTotalPrice() {
      const quantity = parseInt(document.getElementById("itmQnt").value) || 0;
      const price = parseFloat(document.getElementById("itmPrice").value) || 0;
      document.getElementById("totalPrice").value = (quantity * price).toFixed(2);
    }

    document.getElementById("itmQnt").addEventListener("input", updateTotalPrice);
    document.getElementById("itmPrice").addEventListener("input", updateTotalPrice);
  });
</script>
@endpush
@endsection
