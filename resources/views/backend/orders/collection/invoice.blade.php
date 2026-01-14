@extends('backend.master')
@section('title', 'Collection_Invoice_'.$transaction->id)
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
          <h4 class="page-header">Collection Invoice</h4>
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
            <strong>Customer: {{ $order->customer_name ?? 'N/A' }}</strong><br>
            Cottage: {{ $order->cottage->name ?? 'N/A' }}<br>
            Address: {{ $order->cottage->address ?? 'N/A' }}<br>
            Phone: {{ $order->cottage->phone ?? 'N/A' }}
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
          Invoice ID #{{$transaction->id}}<br>
          Sale ID #{{$order->id}}<br>
          Sale Date: {{date('d/m/Y', strtotime($order->created_at))}}<br>
          Collection Date: {{ $transaction->created_at->setTimezone('Africa/Nairobi')->format('d/m/Y') }}<br>
          @php
              $paymentMethod = $transaction->paid_by ?? 'cash';
              $methodNames = [
                  'cash' => 'Cash',
                  'card' => 'Card',
                  'mobile_money' => 'Mobile Money',
                  'bank_transfer' => 'Bank Transfer'
              ];
          @endphp
          <b>Payment Method:</b> {{ $methodNames[$paymentMethod] ?? ucfirst($paymentMethod) }}<br>
          @if($transaction->transaction_id)
          <b>Transaction ID:</b> {{ $transaction->transaction_id }}<br>
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
                <th>Description</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Due Collection for Order #{{$order->id}}<br>
                    <small>Customer: {{ $order->customer_name ?? 'N/A' }} | Cottage: {{$order->cottage->name ?? 'N/A'}}</small>
                </td>
                <td>{{$transaction->amount}}</td>
              </tr>
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
            <b>Customer:</b> {{ $order->customer_name ?? 'N/A' }}<br>
            <b>Cottage:</b> {{$order->cottage->name ?? 'N/A'}}<br>
            <b>Payment Method:</b> {{ $methodNames[$paymentMethod] ?? ucfirst($paymentMethod) }}<br>
            @if($transaction->transaction_id)
            <b>Transaction ID:</b> {{ $transaction->transaction_id }}<br>
            @endif
            <b>Collected By:</b> {{ Auth::user()->name ?? 'System' }}<br>
            <b>Collection Time:</b> {{ date('h:i A', strtotime($transaction->created_at)) }}
          </p>
        </div>
        <!-- /.col -->
        <div class="col-6">
          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td>{{$transaction->amount}}</td>
              </tr>
              <tr>
                <th>Tax (0%)</th>
                <td>0</td>
              </tr>
              <tr>
                <th>Total:</th>
                <td>{{$transaction->amount}}</td>
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
