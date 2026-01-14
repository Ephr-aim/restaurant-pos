@extends('backend.master')

@section('title', 'Collection')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.due.collection',$order->id) }}" method="post" class="accountForm">
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-4">
            <label class="form-label">
              Customer Name
            </label>
            <p class="fw-bold">{{$order->customer_name ?? 'N/A'}}</p>
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label">
              Cottage
            </label>
            <p class="fw-bold">{{$order->cottage->name ?? 'N/A'}}</p>
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label">
              Order ID
            </label>
            <p class="fw-bold"># {{$order->id}}</p>
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label">
              Total Amount
            </label>
            <p class="fw-bold">{{$order->total}}</p>
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label">
              Paid Amount
            </label>
            <p class="fw-bold">{{$order->paid}}</p>
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label">
              Due Amount
            </label>
            <p class="fw-bold text-danger">{{$order->due}}</p>
          </div>
        </div>
        
        <div class="row">
          <div class="mb-3 col-md-6">
            <label for="payment_method" class="form-label">
              Payment Method <span class="text-danger">*</span>
            </label>
            <select class="form-select" name="payment_method" id="payment_method" required>
              <option value="cash">Cash</option>
              <option value="card">Card</option>
              <option value="mobile_money">Mobile Money</option>
            </select>
          </div>
          <div class="mb-3 col-md-6">
            <label for="amount" class="form-label">
              Collection Amount <span class="text-danger">*</span>
            </label>
            <input type="number" class="form-control" name="amount" id="amount" 
                   placeholder="Collection Amount" value="{{ $order->due }}" min="1" max="{{ $order->due }}" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <button type="submit" class="btn btn-success">
              <i class="fas fa-money-bill-wave me-2"></i> Collect Payment
            </button>
            <a href="{{ route('backend.admin.orders.index') }}" class="btn btn-secondary">
              <i class="fas fa-times me-2"></i> Cancel
            </a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
