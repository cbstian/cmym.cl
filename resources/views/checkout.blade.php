@extends('layout.master')

@section('title_1', 'Checkout')
@section('title_2', 'Finalizar Compra')

@section('class-body', 'checkout-container')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb cart-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart') }}">Carrito</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h1 class="checkout-title">Finalizar Compra</h1>
        </div>
    </div>

    {{-- Componente Livewire de Checkout --}}
    <livewire:checkout />
</div>
@endsection
