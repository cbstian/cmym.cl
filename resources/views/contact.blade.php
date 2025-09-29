@extends('layout.master')

@section('title_1','Comercializadora M&M')
@section('title_2','Contacto')

@section('pre_css')
@endsection

@section('css')
@endsection

@section('content')

<!-- Hero Section -->
<div class="container-fluid bg-white">
    <div class="container">
        <div class="row py-5">
            <div class="col-md-12 text-center py-5" data-aos="zoom-in" data-aos-duration="1000">
                <h1 class="montserrat-900 mb-3">CONTÁCTANOS</h1>
                <p class="font-size-18 text-muted">
                    Estamos aquí para ayudarte a encontrar los muebles perfectos para tu hogar.<br>
                    Ponte en contacto con nosotros y comencemos a hacer realidad tu proyecto.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Contact Information Section -->
<div class="container-fluid bg-light">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12 text-center mb-5" data-aos="fade-up" data-aos-duration="800">
                <h2 class="montserrat-900 text-green">INFORMACIÓN DE CONTACTO</h2>
                <p class="font-size-18">Te ofrecemos múltiples formas de comunicarte con nosotros</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <!-- Teléfono -->
            <div class="col-md-4 text-center mb-4" data-aos="flip-up" data-aos-delay="100">
                <div class="bg-white p-4 rounded shadow-sm h-100">
                    <i class="fas fa-phone fa-3x text-green mb-3"></i>
                    <h4 class="montserrat-600 text-dark mb-3">Teléfono</h4>
                    <p class="text-muted mb-2">Llámanos directamente</p>
                    <a href="tel:+56951589643" class="btn btn-outline-green">
                        <i class="fas fa-phone me-2"></i>
                        +56 9 5158 9643
                    </a>
                </div>
            </div>

            <!-- Email -->
            <div class="col-md-4 text-center mb-4" data-aos="flip-up" data-aos-delay="200">
                <div class="bg-white p-4 rounded shadow-sm h-100">
                    <i class="fas fa-envelope fa-3x text-green mb-3"></i>
                    <h4 class="montserrat-600 text-dark mb-3">Correo Electrónico</h4>
                    <p class="text-muted mb-2">Envíanos un mensaje</p>
                    <a href="mailto:cmym.spa@gmail.com" class="btn btn-outline-green">
                        <i class="fas fa-envelope me-2"></i>
                        cmym.spa@gmail.com
                    </a>
                </div>
            </div>

            <!-- Redes Sociales -->
            <div class="col-md-4 text-center mb-4" data-aos="flip-up" data-aos-delay="300">
                <div class="bg-white p-4 rounded shadow-sm h-100">
                    <i class="fas fa-share-alt fa-3x text-green mb-3"></i>
                    <h4 class="montserrat-600 text-dark mb-3">Redes Sociales</h4>
                    <p class="text-muted mb-3">Síguenos y contáctanos</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="https://wa.me/56951589643" target="_blank" class="btn btn-success rounded-circle" title="WhatsApp">
                            <i class="fab fa-whatsapp fa-lg"></i>
                        </a>
                        <a href="#" target="_blank" class="btn btn-primary rounded-circle" title="Facebook">
                            <i class="fab fa-facebook fa-lg"></i>
                        </a>
                        <a href="https://www.instagram.com/cmym.spa" target="_blank" class="btn btn-danger rounded-circle" title="Instagram">
                            <i class="fab fa-instagram fa-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Form Section -->
<div class="container-fluid container-form">
    <div class="container py-5">
        <div class="row align-items-center py-5">
            <div class="col-md-6" data-aos="slide-up" data-aos-duration="800">
                <p class="mb-4 text-yellow">M&M - Comercializadora e Importadora</p>
                <h1 class="montserrat-900 mb-4 text-green text-uppercase">
                    <span class="text-white">¿Tienes alguna</span><br>
                    <span class="text-yellow">consulta o proyecto?</span>
                </h1>
                <p class="text-white mb-4">
                    <b>Completa el formulario y nos pondremos en contacto contigo.</b><br>
                    Cuéntanos sobre tu proyecto y cómo podemos ayudarte a transformar tu espacio.
                </p>

                <!-- Beneficios -->
                <div class="text-white" data-aos="fade-up" data-aos-delay="200">
                    <h5 class="text-yellow mb-3">
                        <i class="fas fa-star me-2"></i>
                        ¿Por qué elegirnos?
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Respuesta rápida y personalizada
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Asesoría especializada en decoración
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Entrega e instalación incluida
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6" data-aos="slide-down" data-aos-duration="800" data-aos-delay="100">
                @livewire('contact-form')
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="container-fluid bg-light">
    @livewire('faq-list')
</div>

@endsection

@section('pre_js')
    @vite('resources/js/aos-app.js')
@endsection

@section('js')
@endsection
