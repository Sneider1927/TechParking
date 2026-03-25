@extends('plantilla.app')

@section('contenido')
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">

                        <div class="card-header">
                            <h3 class="card-title">{{ isset($registro) ? 'Editar Producto' : 'Crear Producto' }}</h3>
                        </div>

                        <div class="card-body">
                            <form action="{{ isset($registro) ? route('productos.update', $registro->id) : route('productos.store') }}"
                                method="POST" enctype="multipart/form-data" id="formProducto">
                                @csrf
                                @if (isset($registro))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="codigo" class="form-label">Código</label>
                                        <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                                            id="codigo" name="codigo" value="{{ old('codigo', $registro->codigo ?? '') }}" required>
                                        @error('codigo')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                            id="nombre" name="nombre" value="{{ old('nombre', $registro->nombre ?? '') }}" required>
                                        @error('nombre')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="precio" class="form-label">Precio</label>
                                        <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" 
                                            id="precio" name="precio" value="{{ old('precio', $registro->precio ?? '') }}" required>
                                        @error('precio')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                            id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $registro->descripcion ?? '') }}</textarea>
                                        @error('descripcion')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="imagen" class="form-label">Imagen
                                            @if (!isset($registro))
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>
                                        <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                                            id="imagen" name="imagen" accept="image/*">
                                        @if (isset($registro) && $registro->imagen)
                                            <small class="text-muted d-block mt-2">Imagen actual: {{ $registro->imagen }}</small>
                                            <img src="{{ asset('uploads/productos/' . $registro->imagen) }}" alt="Producto" style="max-width: 100px; margin-top: 10px;">
                                        @endif
                                        @error('imagen')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-secondary me-md-2"
                                        onclick="window.location.href='{{ route('productos.index') }}'">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>

                        <div class="card-footer clearfix">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.getElementById('menuProductos')?.classList.add('menu-open');
        document.getElementById('itemProducto')?.classList.add('active');
    </script>
@endpush
