@extends('layout.master')

@section('title_1', 'Carrito de Compras')
@section('title_2', 'Mi Carrito')

@section('class-body', 'bg-light')
@section('class-main', 'pt-5')

@section('content')
<div class="container mt-5 pt-5 cart-page">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb cart-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Carrito</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Mi Carrito de Compras
                    </h3>
                    <div>
                        <livewire:cart.cart-counter />
                    </div>
                </div>
                <div class="card-body">
                    <livewire:cart.cart-manager />
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('products') }}" class="btn btn-outline-green">
                    <i class="fas fa-arrow-left me-2"></i>
                    Seguir Comprando
                </a>
                <div class="text-muted">
                    <small>
                        <i class="fas fa-lock me-1"></i>
                        Compra segura y protegida
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
