@extends('backend.master')
@section('title', 'Invoice')
@section('content')
<div class="card">
  <div class="card-body">
    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row mb-4">
        <div class="col-4">
          <h2 class="page-header">
            <img src="{{ assetImage(readconfig('site_logo')) }}" height="40" width="40" alt="Logo"
              class="brand-image img-circle elevation-3" style="opacity: .8"> {{ readConfig('site_name') }}
          </h2>
        </div>
        <div class="col-4">
          <h4 class="page-header">Sale Invoice</h4>
        </div>
        <div class="col-4">
          <small class="float-right text-small">Date: {{date('d/m/Y')}}</small>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info mb-2">
        <!-- /.col -->
        <div class="col-sm-5 invoice-col">
          @if(readConfig('is_show_customer_invoice'))
          To
          <address>
            <strong>Customer: {{ $order->customer_name ?? "N/A" }}</strong><br>
            Cottage: {{$order->cottage->name??"N/A"}}<br>
            Address: {{$order->cottage->address??"N/A"}}<br>
            Phone: {{$order->cottage->phone??"N/A"}}<br>
          </address>
          @endif
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          From
          <address>
            @if(readConfig('is_show_site_invoice'))<strong>Name:{{ readConfig('site_name') }}</strong><br> @endif
            @if(readConfig('is_show_address_invoice'))Address: {{ readConfig('contact_address') }}<br>@endif
            @if(readConfig('is_show_phone_invoice'))Phone: {{ readConfig('contact_phone') }}<br>@endif
            @if(readConfig('is_show_email_invoice'))Email: {{ readConfig('contact_email') }}<br>@endif
          </address>
        </div>
        <div class="col-sm-3 invoice-col">
          Info <br>
          Sale ID #{{$order->id}}<br>
          Sale Date: {{ $order->created_at->setTimezone('Africa/Nairobi')->format('d/m/Y') }}<br>
          @php
              $paymentMethod = $order->transactions->count() > 0 ? $order->transactions->first()->paid_by : "cash";
              $methodNames = [
                  "cash" => "Cash",
                  "card" => "Card",
                  "mobile_money" => "Mobile Money",
                  "bank_transfer" => "Bank Transfer"
              ];
          @endphp
          <b>Payment Method:</b> {{ $methodNames[$paymentMethod] ?? ucfirst($paymentMethod) }}<br>
          @if($order->transactions->count() > 0 && $order->transactions->first()->transaction_id)
          <b>Transaction ID:</b> {{ $order->transactions->first()->transaction_id }}<br>
          @endif
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-12 table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->products as $key=> $product)
              <tr>
                <td>{{++$key}}</td>
                <td>{{$product->product->name}}</td>
                <td>{{$product->quantity}}</td>
                <td>{{$product->price}}</td>
                <td>{{$product->quantity*$product->price}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-6">
          <p class="lead">Payment Details:</p>
          <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
            <b>Payment Method:</b> {{ $methodNames[$paymentMethod] ?? ucfirst($paymentMethod) }}<br>
            @if($order->transactions->count() > 0 && $order->transactions->first()->transaction_id)
            <b>Transaction ID:</b> {{ $order->transactions->first()->transaction_id }}<br>
            @endif
            <b>Status:</b> {{ $order->status ? "Paid" : "Due" }}<br>
            <b>Paid Amount:</b> {{ $order->paid }}<br>
            <b>Due Amount:</b> {{ $order->due }}<br>
            @if(readConfig('is_show_note_invoice')){{ readConfig('note_to_customer_invoice') }}@endif
          </p>
        </div>
        <!-- /.col -->
        <div class="col-6">
          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td>{{$order->sub_total}}</td>
              </tr>
              <tr>
                <th>Discount</th>
                <td>{{$order->discount}}</td>
              </tr>
              <tr>
                <th>Total:</th>
                <td>{{$order->total}}</td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-12">
          <a href="#" onclick="window.print();" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
        </div>
      </div>
    </section>
  </div>
</div>
@endsection
