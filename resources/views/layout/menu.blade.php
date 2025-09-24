<div class="fixed-top">
    <nav class="navbar navbar-expand-lg py-0 bg-gray">
        <div class="container py-2">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/mym-logo.svg') }}" class="img-fluid" style="max-height: 60px;" alt="Nazcastore">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item px-3 d-flex align-items-center me-3 @if( Request::is('/') ) active @endif">
                        <a class="nav-link" aria-current="page" href="{{ route('home') }}">Inicio</a>
                    </li>
                    <li class="nav-item px-3 d-flex align-items-center me-3 @if( Request::is('quienes-somos') ) active @endif">
                        <a class="nav-link" aria-current="page" href="{{ route('about') }}">Nosotros</a>
                    </li>
                    <li class="nav-item px-3 d-flex align-items-center me-3 @if( Request::is('productos*') ) active @endif">
                        <a class="nav-link" aria-current="page" href="{{ route('products') }}">Productos</a>
                    </li>
                    <li class="nav-item px-3 d-flex align-items-center me-3 @if( Request::is('contacto') ) active @endif">
                        <a class="nav-link" aria-current="page" href="{{ route('contact') }}">Contacto</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link p-0" aria-current="page" href="{{ route('home') }}">
                            <img src="{{ asset('images/cart-icon.svg') }}" class="img-fluid" style="max-height: 40px;" alt="Contacto">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid bg-green">
        <div class="container">
            <div class="row justify-content-center py-2">
                <div class="col-md-3 text-center">
                    <a href="tel:+56951589643" target="_blank" class="text-white text-decoration-none">
                        <img src="{{ asset('images/phone.svg') }}" class="img-fluid">
                        +56 9 5158 9643
                    </a>
                </div>
                <div class="col-md-3 text-center">
                    <a href="mailto:cmym.spa@gmail.com" target="_blank" class="text-white text-decoration-none">
                        <img src="{{ asset('images/mail.svg') }}" class="img-fluid">
                        cmym.spa@gmail.com
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
