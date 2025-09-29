@extends('layout.master')

@section('title_1','Comercializadora M&M')
@section('title_2','Sobre nosotros')

@section('pre_css')
@endsection

@section('css')
@endsection

@section('content')

<!-- Hero Section -->
<section class="about-hero-section">
    <div class="container">
        <h1 class="hero-title" data-aos="zoom-in" data-aos-duration="1000">SOBRE NOSOTROS</h1>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5" data-aos="fade-up" data-aos-duration="800">
                <h2 class="section-title green-text">¿POR QUE ELEGIRNOS?</h2>
                <p class="lead text-muted">Porque combinamos calidad, diseño y durabilidad para que disfrutes tu<br>terraza con estilo y comodidad, todo el año.</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Fast & Free Shipping -->
            <div class="col-md-3 col-sm-6" data-aos="flip-up" data-aos-delay="100">
                <div class="feature-card">
                    <i class="fa-solid fa-award text-green feature-icon"></i>
                    <h5 class="feature-title text-gray">Calidad garantizada</h5>
                    <p class="feature-description">Ofrecemos muebles y accesorios diseñados con materiales resistentes, pensados para soportar el clima y mantenerse como nuevos por más tiempo.</p>
                </div>
            </div>

            <!-- Easy to Shop -->
            <div class="col-md-3 col-sm-6" data-aos="flip-up" data-aos-delay="200">
                <div class="feature-card">
                    <i class="fa-solid fa-couch text-green feature-icon"></i>
                    <h5 class="feature-title text-gray">Diseño pensado para ti</h5>
                    <p class="feature-description">Nuestros productos combinan estilo, comodidad y funcionalidad para que tu terraza sea un espacio único y acogedor.</p>
                </div>
            </div>

            <!-- 24/7 Support -->
            <div class="col-md-3 col-sm-6" data-aos="flip-up" data-aos-delay="300">
                <div class="feature-card">
                    <i class="fa-solid fa-headset text-green feature-icon"></i>
                    <h5 class="feature-title text-gray">Atención personalizada</h5>
                    <p class="feature-description">Te acompañamos en cada paso del proceso, desde la elección de los productos hasta la instalación, asegurando una experiencia sin complicaciones.</p>
                </div>
            </div>

            <!-- Hassle Free Returns -->
            <div class="col-md-3 col-sm-6" data-aos="flip-up" data-aos-delay="400">
                <div class="feature-card">
                    <i class="fa-solid fa-truck-fast text-green feature-icon"></i>
                    <h5 class="feature-title text-gray">Entrega en todo Chile</h5>
                    <p class="feature-description">Llevamos tus muebles donde los necesites, con envíos rápidos y seguros a cualquier región del país.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="about-section-1">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-xl-5 col-lg-6 py-5 d-flex align-items-center h-400" data-aos="slide-up" data-aos-duration="800">
                <div class="mission-content py-5 text-center text-lg-start">
                    <h2 class="section-title text-white">NUESTRA <span class="green-text">MISIÓN</span></h2>
                    <p class="lead text-white">
                        Nuestra misión es transformar las terrazas en lugares de encuentro y bienestar, ofreciendo
                        muebles y accesorios de calidad que combinan diseño,
                        resistencia y comodidad, adaptándose a las necesidades de cada cliente en todo Chile.
                    </p>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-5 col-lg-6 text-center bg-arrow-1 d-flex align-items-center justify-content-center h-400" data-aos="zoom-in" data-aos-delay="200">
                <img src="{{ asset('images/icon-nosotros-1.svg') }}" alt="Misión" class="section-icon">
            </div>
        </div>
    </div>
</section>

<!-- Vision Section -->
<section class="about-section-2">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-xl-5 col-lg-6 text-center bg-arrow-2 d-lg-flex align-items-center justify-content-center h-400 d-none" data-aos="zoom-in" data-aos-delay="200">
                <img src="{{ asset('images/icon-nosotros-2.svg') }}" alt="Visión" class="section-icon">
            </div>
            <div class="col-xxl-4 col-xl-5 col-lg-6 py-5 d-flex align-items-center h-400" data-aos="slide-down" data-aos-duration="800">
                <div class="mission-content py-5 text-center text-lg-end">
                    <h2 class="section-title">NUESTRA <span style="color: #fff;">VISIÓN</span></h2>
                    <p class="lead">
                        Nuestra visión es consolidarnos como la marca líder en mobiliario para terrazas en Chile, destacando
                        por la innovación en nuestros productos, la cercanía en el servicio y la capacidad de inspirar a
                        cada persona a crear el espacio exterior de sus sueños.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="about-section-3">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-xl-5 col-lg-6 py-5 d-flex align-items-center h-400" data-aos="fade-up" data-aos-duration="800">
                <div class="mission-content py-5 text-center text-lg-start">
                    <h2 class="section-title text-dark">NUESTROS <span class="green-text">VALORES</span></h2>
                    <p class="lead text-dark">
                        Nuestros valores se basan en el compromiso con la calidad, la confianza y la cercanía con quienes nos eligen.
                        Creemos en la innovación constante, en escuchar a nuestros clientes y en entregar soluciones que no solo
                        embellezcan los espacios, sino que también generen experiencias duraderas y significativas
                    </p>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-5 col-lg-6 text-center bg-arrow-3 d-flex align-items-center justify-content-center h-400" data-aos="zoom-in" data-aos-delay="200">
                <img src="{{ asset('images/icon-nosotros-3.svg') }}" alt="Valores" class="section-icon">
            </div>
        </div>
    </div>
</section>

@endsection

@section('pre_js')
    @vite('resources/js/aos-app.js')
@endsection

@section('js')
@endsection
