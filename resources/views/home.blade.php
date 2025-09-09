@extends('layout.master')

@section('title_1','Comercializadora M&M')
@section('title_2','Inicio')

@section('pre_css')
@endsection

@section('css')
@endsection

@section('content')

<div class="container-fluid bg-white">
    <div class="container banner-home">
        <div class="row py-5">
            <div class="col-md-12 px-0 py-5">
                <h2 class="montserrat-900 mb-0">ENCUENTRA LO MEJOR</h2>
                <h1 class="montserrat-900">PARA TU HOGAR</h1>
                <p class="font-size-16">
                    Encuentra sillas, mesas y la mejor iluminación.<br>
                    La terraza de tus sueños al alcance de un clic.
                </p>
                <a href="" class="btn btn-primary-green text-white montserrat-600">Ver más</a>
            </div>
        </div>
    </div>
</div>

@livewire('product-grid')

<div class="container-fluid bg-beige">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-md-6 px-0 text-start">
                <p class="font-size-18">M&M - Comercializadora e Importadora</p>
                <h2 class="text-gray mb-0 montserrat-900">TE ENTREGAMOS LA</h2>
                <h1 class="text-brown montserrat-900">MEJOR EXPERIENCIA</h1>
                <p class="font-size-18">
                    Encuentra sillas, mesas y la mejor iluminación. La terraza de<br>
                    tus sueños al alcance de un clic.
                </p>
                <ul class="font-size-18">
                    <li>Entrega a domicilio</li>
                    <li>Acompañamiento completo</li>
                    <li>Confiables y cumplidores</li>
                </ul>
            </div>
            <div class="col-md-6 px-0 text-start">
                <div class="row g-2">
                    <div class="col-6 text-end">
                        <img src="{{ asset('images/estufa-1.png') }}" class="img-fluid mb-2" alt="">
                        <img src="{{ asset('images/terraza-1.png') }}" class="img-fluid" alt="">
                    </div>
                    <div class="col-6 text-start">
                        <img src="{{ asset('images/sillas-1.png') }}" class="img-fluid" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="container">
        <div class="row pt-5">
            <div class="col-md-12 px-0 py-5 text-center">
                <h1 class="montserrat-900 mb-0 text-green">¿COMO LO HACEMOS?</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="{{ asset('images/1.svg') }}" class="img-fluid" alt="">
            </div>
            <div class="col-md-4 text-center">
                <img src="{{ asset('images/2.svg') }}" class="img-fluid" alt="">
            </div>
            <div class="col-md-4 text-center">
                <img src="{{ asset('images/3.svg') }}" class="img-fluid" alt="">
            </div>
        </div>
        <div class="row py-5">
            <div class="col-md-4 text-center">
                <img src="{{ asset('images/experiencia.svg') }}" class="img-fluid mb-3" alt="">
                <h4 class="text-uppercase mona-sans-700 text-green mb-3">EXPERIENCIA<br>EN EL RUBRO</h4>
                <p class="mona-sans-500 text-gray font-size-22">
                    Nuestra experiencia nos<br>permite entregarte la mejor<br>atención y disponibilidad
                </p>
            </div>
            <div class="col-md-4 text-center">
                <img src="{{ asset('images/enfoque.svg') }}" class="img-fluid mb-3" alt="">
                <h4 class="text-uppercase mona-sans-700 text-green mb-3">ENFOQUE ÉTICO Y<br>HUMANO</h4>
                <p class="mona-sans-500 text-gray font-size-22">
                    Cercanía,<br>confidencialidad y<br>compromiso
                </p>
            </div>
            <div class="col-md-4 text-center">
                <img src="{{ asset('images/union.svg') }}" class="img-fluid mb-3" alt="">
                <h4 class="text-uppercase mona-sans-700 text-green mb-3">ACOMPAÑAMIENTO<br>COMPLETO</h4>
                <p class="mona-sans-500 text-gray font-size-22">
                    Seguimos tu proceso de cerca<br>y profesionalmente en cada<br>etapa
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid container-form">
    <div class="container py-5">
        <div class="row py-5">
            <div class="col-md-6">
                <p class="mb-4 text-yellow">M&M - Comercializadora e Importadora</p>
                <h1 class="montserrat-900 mb-4 text-green text-uppercase">
                    <span class="text-white">¿Listo para</span><br>
                    <span class="text-yellow">renovar tu hogar?</span>
                </h1>
                <p class="text-white">
                    <b>Inspírate, sueña en grande y déjanos ayudarte a hacerlo realidad.</b><br>
                    Completa el formulario y comienza hoy tu transformación.
                </p>
            </div>
            <div class="col-md-6">
                @livewire('contact-form')
            </div>
        </div>
    </div>
</div>

@livewire('faq-list')

@endsection

@section('pre_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
@endsection

@section('js')

@endsection
