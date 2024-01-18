@extends('layouts.format')

@section('title', __('Export Services'))

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
      <h1>SERVICIOS</h1>
      <div id="company" class="clearfix">
        <div>Acuática Azul</div>
        <div>{{ settings()->company_address }}</div>
        <div>(602) 519-0450</div>
        <div><a href="mailto:company@example.com">company@example.com</a></div>
      </div>
      <div id="project">
        <div><span>PROJECT</span> Website development</div>
        <div><span>CLIENT</span> John Doe</div>
        <div><span>ADDRESS</span> 796 Silver Harbour, TX 79273, US</div>
        <div><span>EMAIL</span> <a href="mailto:john@example.com">john@example.com</a></div>
        <div><span>DATE</span> August 17, 2015</div>
        <div><span>DUE DATE</span> September 17, 2015</div>
      </div>
    </header>
    <main>
      <table>
        <thead>
          <tr>
            <th class="desc">DESCRIPTION</th>
            <th style="text-align: center;">PRECIO</th>
          </tr>
        </thead>
        <tbody>
          @foreach($services as $key => $service)
          <tr>
            <td class="desc">{{ $service->name }}<br>{{ $service->note }}</td>
            <td style="text-align: center;">${{ $service->price }}</td>
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

