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
        <div class="row py-4 py-lg-5">
            <div class="col-md-12 px-0 py-4 py-lg-5" data-aos="fade-right" data-aos-duration="1000">
                <h2 class="montserrat-900 mb-0">ENCUENTRA LO MEJOR</h2>
                <h1 class="montserrat-900">PARA TU HOGAR</h1>
                <p class="font-size-16">
                    Encuentra sillas, mesas y la mejor iluminación.<br>
                    La terraza de tus sueños al alcance de un clic.
                </p>
                <a href="{{ route('products') }}" class="btn btn-primary-green text-white montserrat-600" data-aos="zoom-in" data-aos-delay="200">
                    <i class="fas fa-eye me-2"></i>Ver más
                </a>
            </div>
        </div>
    </div>
</div>

@livewire('product-grid', ['perPage' => 8, 'showTitle' => true])

<div class="container-fluid bg-beige">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-md-6 px-0 text-start" data-aos="flip-up" data-aos-duration="800">
                <p class="font-size-18">M&M - Comercializadora e Importadora</p>
                <h2 class="text-gray mb-0 montserrat-900">TE ENTREGAMOS LA</h2>
                <h1 class="text-brown montserrat-900">MEJOR EXPERIENCIA</h1>
                <p class="font-size-18">
                    Encuentra sillas, mesas y la mejor iluminación. La terraza de<br>
                    tus sueños al alcance de un clic.
                </p>
                <ul class="font-size-18 list-unstyled" data-aos="flip-up" data-aos-delay="200">
                    <li class="mb-2"><i class="fas fa-truck text-success me-2"></i>Entrega a domicilio</li>
                    <li class="mb-2"><i class="fas fa-hands-helping text-success me-2"></i>Acompañamiento completo</li>
                    <li class="mb-2"><i class="fas fa-shield-alt text-success me-2"></i>Confiables y cumplidores</li>
                </ul>
            </div>
            <div class="col-md-6 px-0 text-start" data-aos="fade-down" data-aos-duration="800" data-aos-delay="300">
                <div class="row g-2">
                    <div class="col-6 text-end">
                        <img src="{{ asset('images/estufa-1.png') }}" class="img-fluid mb-2" alt="" data-aos="zoom-in" data-aos-delay="400">
                        <img src="{{ asset('images/terraza-1.png') }}" class="img-fluid" alt="" data-aos="zoom-in" data-aos-delay="600">
                    </div>
                    <div class="col-6 text-start">
                        <img src="{{ asset('images/sillas-1.png') }}" class="img-fluid" alt="" data-aos="zoom-in" data-aos-delay="500">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="container">
        <div class="row pt-5">
            <div class="col-md-12 px-0 py-5 text-center" data-aos="fade-in" data-aos-duration="800">
                <h1 class="montserrat-900 mb-0 text-green">¿COMO LO HACEMOS?</h1>
            </div>
        </div>
        <div class="row d-none d-lg-flex">
            <div class="col-md-4 text-center" data-aos="fade-in" data-aos-delay="100">
                <img src="{{ asset('images/1.svg') }}" class="img-fluid" alt="">
            </div>
            <div class="col-md-4 text-center" data-aos="fade-in" data-aos-delay="200">
                <img src="{{ asset('images/2.svg') }}" class="img-fluid" alt="">
            </div>
            <div class="col-md-4 text-center" data-aos="fade-in" data-aos-delay="300">
                <img src="{{ asset('images/3.svg') }}" class="img-fluid" alt="">
            </div>
        </div>
        <div class="row py-4 py-lg-5">
            <div class="col-md-4 text-center mb-4" data-aos="flip-up" data-aos-delay="100">
                <img src="{{ asset('images/experiencia.svg') }}" class="img-fluid mb-3" alt="">
                <h4 class="text-uppercase mona-sans-700 text-green mb-3">EXPERIENCIA<br>EN EL RUBRO</h4>
                <p class="mona-sans-500 text-gray font-size-22">
                    Nuestra experiencia nos<br>permite entregarte la mejor<br>atención y disponibilidad
                </p>
            </div>
            <div class="col-md-4 text-center mb-4" data-aos="flip-up" data-aos-delay="200">
                <img src="{{ asset('images/enfoque.svg') }}" class="img-fluid mb-3" alt="">
                <h4 class="text-uppercase mona-sans-700 text-green mb-3">ENFOQUE ÉTICO Y<br>HUMANO</h4>
                <p class="mona-sans-500 text-gray font-size-22">
                    Cercanía,<br>confidencialidad y<br>compromiso
                </p>
            </div>
            <div class="col-md-4 text-center mb-4" data-aos="flip-up" data-aos-delay="300">
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
        <div class="row align-items-center py-5">
            <div class="col-md-6" data-aos="fade-up" data-aos-duration="800">
                <p class="mb-4 text-yellow">M&M - Comercializadora e Importadora</p>
                <h1 class="montserrat-900 mb-4 text-green text-uppercase">
                    <span class="text-white">¿Listo para</span><br>
                    <span class="text-yellow">renovar tu hogar?</span>
                </h1>
                <p class="text-white">
                    <b>Inspírate, sueña en grande y déjanos ayudarte a hacerlo realidad.</b><br>
                    Completa el formulario y comienza hoy tu transformación.
                </p>
                <div class="my-4">
                    <a href="{{ route('contact') }}" class="btn btn-outline-light" data-aos="zoom-in" data-aos-delay="200">
                        <i class="fas fa-phone me-2"></i>
                        Ver más formas de contacto
                    </a>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-down" data-aos-duration="800" data-aos-delay="100">
                @livewire('contact-form')
            </div>
        </div>
    </div>
</div>

@livewire('faq-list')

@endsection

@section('pre_js')
    @vite('resources/js/aos-app.js')
@endsection

@section('js')
@endsection
