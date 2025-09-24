@extends('layout.master')

@section('title_1','Comercializadora M&M')
@section('title_2','Sobre nosotros')

@section('pre_css')
@endsection

@section('css')
@endsection

@section('content')

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">SOBRE NOSOTROS</h1>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title green-text">¿POR QUE ELEGIRNOS?</h2>
                <p class="lead text-muted">Porque combinamos calidad, diseño y durabilidad para que disfrutes tu<br>terraza con estilo y comodidad, todo el año.</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Fast & Free Shipping -->
            <div class="col-md-3 col-sm-6">
                <div class="feature-card">
                    <img src="path/to/shipping-icon.png" alt="Fast & Free Shipping" class="feature-icon">
                    <h5 class="feature-title">Fast & Free Shipping</h5>
                    <p class="feature-description">Donec mattis porta eros, aliquet finibus risus interdum at. Nulla vivethe as it was</p>
                </div>
            </div>

            <!-- Easy to Shop -->
            <div class="col-md-3 col-sm-6">
                <div class="feature-card">
                    <img src="path/to/shop-icon.png" alt="Easy to Shop" class="feature-icon">
                    <h5 class="feature-title">Easy to Shop</h5>
                    <p class="feature-description">Donec mattis porta eros, aliquet finibus risus interdum at. Nulla vivethe as it was</p>
                </div>
            </div>

            <!-- 24/7 Support -->
            <div class="col-md-3 col-sm-6">
                <div class="feature-card">
                    <img src="path/to/support-icon.png" alt="24/7 Support" class="feature-icon">
                    <h5 class="feature-title">24/7 Support</h5>
                    <p class="feature-description">Donec mattis porta eros, aliquet finibus risus interdum at. Nulla vivethe as it was</p>
                </div>
            </div>

            <!-- Hassle Free Returns -->
            <div class="col-md-3 col-sm-6">
                <div class="feature-card">
                    <img src="path/to/returns-icon.png" alt="Hassle Free Returns" class="feature-icon">
                    <h5 class="feature-title">Hassle Free Returns</h5>
                    <p class="feature-description">Donec mattis porta eros, aliquet finibus risus interdum at. Nulla vivethe as it was</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="mission-section py-5">
    <div class="mission-shape"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="mission-content">
                    <h2 class="section-title">NUESTRA <span class="green-text">MISIÓN</span></h2>
                    <p class="lead">Proteger los activos digitales de nuestros clientes asegurando el cumplimiento normativo, la continuidad operativa y una cultura organizacional consciente de los riesgos tecnológicos.</p>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <img src="path/to/mission-icon.png" alt="Misión" class="section-icon">
            </div>
        </div>
    </div>
</section>

<!-- Vision Section -->
<section class="vision-section py-5">
    <div class="vision-shape"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center order-lg-1 order-2">
                <img src="path/to/vision-icon.png" alt="Visión" class="section-icon">
            </div>
            <div class="col-lg-8 order-lg-2 order-1">
                <div class="vision-content text-lg-end">
                    <h2 class="section-title">NUESTRA <span style="color: #2c5530;">VISIÓN</span></h2>
                    <p class="lead">Proteger los activos digitales de nuestros clientes asegurando el cumplimiento normativo, la continuidad operativa y una cultura organizacional consciente de los riesgos tecnológicos.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="values-section py-5">
    <div class="values-shape"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="values-content">
                    <h2 class="section-title text-dark">NUESTROS <span class="green-text">VALORES</span></h2>
                    <p class="lead text-dark">Proteger los activos digitales de nuestros clientes asegurando el cumplimiento normativo, la continuidad operativa y una cultura organizacional consciente de los riesgos tecnológicos.</p>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <img src="path/to/values-icon.png" alt="Valores" class="section-icon">
            </div>
        </div>
    </div>
</section>

@endsection

@section('pre_js')
@endsection

@section('js')
@endsection
