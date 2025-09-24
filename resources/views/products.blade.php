@extends('layout.master')

@section('title_1','Comercializadora M&M')
@section('title_2','Productos')

@section('pre_css')
@endsection

@section('css')
@endsection

@section('content')

<div class="container-fluid bg-white">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12 px-0 text-center pb-3">
                <p class="font-size-18 text-gray mb-2">M&M - Comercializadora e Importadora</p>
                <h1 class="montserrat-900 mb-3 text-green">NUESTROS PRODUCTOS</h1>
                <p class="font-size-18 text-gray">
                    Descubre nuestra amplia gama de productos para tu hogar.<br>
                    Sillas, mesas, estufas, toldos y mucho más para crear el espacio perfecto.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Componente de grilla de productos --}}
@livewire('product-grid', ['perPage' => 12, 'showTitle' => false])

<div class="container-fluid bg-beige">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="montserrat-900 text-green mb-4">¿No encuentras lo que buscas?</h3>
                <p class="font-size-18 text-gray mb-4">
                    Contamos con una amplia red de proveedores que nos permite conseguir
                    productos específicos según tus necesidades. <strong>¡Contáctanos!</strong>
                </p>
                <a href="{{ route('contact') }}" class="btn btn-primary-green text-white montserrat-600">
                    Contactar ahora
                </a>
            </div>
            <div class="col-md-6 text-center">
                <img src="{{ asset('images/sillas-1.png') }}" class="img-fluid" alt="Productos M&M">
            </div>
        </div>
    </div>
</div>

<div class="container-fluid bg-white">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3 class="montserrat-900 text-green mb-4">¿Por qué elegir nuestros productos?</h3>
            </div>
        </div>
        <div class="row py-3">
            <div class="col-md-4 text-center mb-4">
                <div class="bg-beige p-4 rounded-4 h-100">
                    <img src="{{ asset('images/experiencia.svg') }}" class="img-fluid mb-3" alt="Calidad garantizada">
                    <h5 class="montserrat-700 text-green mb-3">CALIDAD GARANTIZADA</h5>
                    <p class="mona-sans-500 text-gray">
                        Seleccionamos cuidadosamente cada producto para asegurar
                        la mejor calidad y durabilidad.
                    </p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="bg-beige p-4 rounded-4 h-100">
                    <img src="{{ asset('images/enfoque.svg') }}" class="img-fluid mb-3" alt="Variedad de estilos">
                    <h5 class="montserrat-700 text-green mb-3">VARIEDAD DE ESTILOS</h5>
                    <p class="mona-sans-500 text-gray">
                        Desde clásico hasta moderno, tenemos productos que se
                        adaptan a todos los gustos y espacios.
                    </p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="bg-beige p-4 rounded-4 h-100">
                    <img src="{{ asset('images/union.svg') }}" class="img-fluid mb-3" alt="Entrega a domicilio">
                    <h5 class="montserrat-700 text-green mb-3">ENTREGA A DOMICILIO</h5>
                    <p class="mona-sans-500 text-gray">
                        Llevamos tus productos directamente a tu hogar con
                        el cuidado que se merecen.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pre_js')
@endsection

@section('js')
@endsection
