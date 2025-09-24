<div class="bg-white p-5 shadow">
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form wire:submit="submit">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label text-dark fw-bold">Nombre *</label>
                <input type="text"
                       class="form-control @error('nombre') is-invalid @enderror"
                       id="nombre"
                       wire:model="nombre"
                       placeholder="Tu nombre completo">
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="correo" class="form-label text-dark fw-bold">Correo electrónico *</label>
                <input type="email"
                       class="form-control @error('correo') is-invalid @enderror"
                       id="correo"
                       wire:model="correo"
                       placeholder="tu@email.com">
                @error('correo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="telefono" class="form-label text-dark fw-bold">Teléfono</label>
                <input type="text"
                       class="form-control @error('telefono') is-invalid @enderror"
                       id="telefono"
                       wire:model="telefono"
                       placeholder="+56 9 1234 5678">
                @error('telefono')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="direccion" class="form-label text-dark fw-bold">Dirección</label>
                <input type="text"
                       class="form-control @error('direccion') is-invalid @enderror"
                       id="direccion"
                       wire:model="direccion"
                       placeholder="Tu dirección">
                @error('direccion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="mensaje" class="form-label text-dark fw-bold">Mensaje *</label>
            <textarea class="form-control @error('mensaje') is-invalid @enderror"
                      id="mensaje"
                      wire:model="mensaje"
                      rows="4"
                      placeholder="Cuéntanos qué necesitas para tu hogar..."></textarea>
            @error('mensaje')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit"
                    class="btn btn-primary-green text-white fw-bold py-3"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>
                    <i class="fas fa-paper-plane me-2"></i>
                    Enviar mensaje
                </span>
                <span wire:loading>
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Enviando...
                </span>
            </button>
        </div>
    </form>
</div>
