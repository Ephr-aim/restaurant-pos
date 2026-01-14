@extends('backend.master')

@section('title', 'Product Purchase')

@section('content')
<div class="card">
  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row g-4">
      <div class="col-md-12">
        <div id="purchase"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('style')
<style>
  .react-datepicker-wrapper {
    width: 100%;
    box-sizing: border-box;
  }
</style>
@endpush

@push('script')
    <script src="{{ asset('build/assets/app-13470d73.js') }}"></script>
@endpush
