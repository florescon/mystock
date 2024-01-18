@extends('layouts.format')

@section('title', __('Export Services'))

@section('style')
    <link rel="stylesheet" href="{{ asset('css_custom/example2.css') }}">
@endsection

@section('content')
    <header class="clearfix">
      <div id="logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}">
        </a>
      </div>
      <div id="company">
        <h2 class="name">Acuatica Azul</h2>
        <div>{{ settings()->company_phone }}</div>
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div id="client">
          <div class="to">CONTACTO:</div>
          <div class="address">{{ settings()->company_address }}</div>
          <div class="email"><a href="#">{{ settings()->company_email }}</a></div>
        </div>
        <div id="invoice">
          <h1>SERVICIOS</h1>
        </div>
      </div>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="no" style="text-align: center;">#</th>
            <th class="desc">DESCRIPCIÓN</th>
            <th class="unit" style="text-align: center;">COSTO</th>
          </tr>
        </thead>
        <tbody>
            @foreach($services as $key => $service)
              <tr>
                <td class="no" style="text-align: center;">0{{ $key+1 }}</td>
                <td class="desc" ><h3>{{ $service->name }}</h3>{{ $service->note }}</td>
                <td class="unit" style="text-align: center;">${{ $service->price }}</td>
              </tr>
            @endforeach
        </tbody>
      </table>
      <div id="thanks">¡Los esperamos!</div>
      <div id="notices">
        <div>NOTA:</div>
        <div class="notice"></div>
      </div>
    </main>
   
@endsection

@section('scripts')
@endsection

