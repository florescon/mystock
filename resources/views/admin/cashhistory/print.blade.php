@extends('layouts.format')

@section('title', __('Print Cash Out'))

@section('style')
    <link rel="stylesheet" href="{{ asset('css_custom/example.css') }}">
@endsection

@section('content')

    <header class="clearfix">
      <div id="logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}">
        </a>
      </div>
      <h1>CORTE DE CAJA #{{ $cash->id }}</h1>
      <div id="company" class="clearfix">
        <div>Acuática Azul</div>
        <div>{{ settings()->company_address }}</div>
        <div>{{ settings()->company_phone }}</div>
        <div><a href="#">{{ settings()->company_email }}</a></div>
      </div>
      <div id="project">
        <div><span>CREADO A:</span> {{ $cash->created_at }}</div>
        <br>
        <div><span>EFECTIVO</span> {{ $cash->total_cash + $cash->total_incomes - $cash->total_expenses }} </div>
        <div><span>OTROS M.</span> {{ $cash->total_other }} </div>
        <br>
      </div>
    </header>
    <main>
      
      <table>
        <thead>
          <tr style="background-color: gray;">
            <th style="text-align: center; color: white;" colspan="3">INGRESOS Y EGRESOS</th>
          </tr>
          <tr>
            <th class="desc">ID</th>
            <th style="text-align: center;">MONTO</th>
            <th style="text-align: center;">TIPO</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cash->expenses as $key => $expense)
          <tr>
            <td class="desc">#{{ $expense->id }}</td>
            <td style="text-align: center;">{{ $expense->is_expense ? '-'. $expense->amount : $expense->amount}}</td>
            <td style="text-align: center;">{{ $expense->is_expense ? 'Egreso' : 'Ingreso' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <table style="margin-top: 20px;">
        <thead>
          <tr style="background-color: gray;">
            <th style="text-align: center; color: white;" colspan="3">PAGOS DE VENTAS</th>
          </tr>
          <tr>
            <th class="desc">ID</th>
            <th style="text-align: center;">MONTO</th>
            <th style="text-align: center;">METODO DE PAGO</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cash->sale_payments as $key => $sale_payment)
          <tr>
            <td class="desc">#{{ $sale_payment->id }}</td>
            <td style="text-align: center;">{{ $sale_payment->amount }}</td>
            <td style="text-align: center;">{{ __($sale_payment->payment_method) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <table style="margin-top: 20px;">
        <thead>
          <tr style="background-color: gray;">
            <th style="text-align: center; color: white;" colspan="2">VENTAS</th>
          </tr>
          <tr>
            <th class="desc">ID</th>
            <th style="text-align: center;">CLIENTE</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cash->sales as $key => $sale)
          <tr>
            <td class="desc">#{{ $sale->id }}<br>{{ $sale->reference }}</td>
            <td style="text-align: center;">{{ optional($sale->customer)->name }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div id="notices">
        <div>Nota:</div>
        <div class="notice"></div>
      </div>
    </main>
   
@endsection

@section('scripts')
@endsection

