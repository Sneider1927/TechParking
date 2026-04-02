@extends('plantilla.app')

@section('contenido')
<div class="app-content">
    <div class="container-fluid">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); padding: 20px; border-radius: 10px; color: white; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h1 style="margin: 0; font-size: 28px;">
                <i class="bi bi-tag" style="font-size: 35px; margin-right: 15px;"></i>Configuración de Tarifas
            </h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Solo administradores pueden editar las tarifas del estacionamiento</p>
        </div>

        @role('administrador')
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background-color: #ecf0f1; padding: 20px; border-bottom: 3px solid #9b59b6;">
                        <h5 style="margin: 0; color: #2c3e50; font-weight: bold;">
                            <i class="bi bi-sliders"></i> Editar Tarifas por Hora
                        </h5>
                    </div>

                    <div class="card-body">
                        @if (Session::has('mensaje'))
                            <div class="alert alert-success alert-dismissible fade show" style="background-color: #d4edda; border-color: #27ae60; color: #155724;">
                                <i class="bi bi-check-circle-fill"></i> {{ Session::get('mensaje') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('parqueadero.tarifas.actualizar') }}" method="POST">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card" style="border: 1px solid #3498db; border-radius: 8px; overflow: hidden;">
                                        <div style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; padding: 15px;">
                                            <i class="bi bi-car-front-fill" style="font-size: 24px; margin-right: 10px;"></i>
                                            <strong>Carro</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Tarifa por hora ($)</label>
                                                <input type="number" name="carro" class="form-control @error('carro') is-invalid @enderror" value="{{ $tarifas['carro'] ?? 3000 }}" min="100" required style="font-size: 18px; font-weight: bold;">
                                                @error('carro')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card" style="border: 1px solid #f39c12; border-radius: 8px; overflow: hidden;">
                                        <div style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; padding: 15px;">
                                            <i class="bi bi-bootstrap-fill" style="font-size: 24px; margin-right: 10px;"></i>
                                            <strong>Moto</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Tarifa por hora ($)</label>
                                                <input type="number" name="moto" class="form-control @error('moto') is-invalid @enderror" value="{{ $tarifas['moto'] ?? 1000 }}" min="100" required style="font-size: 18px; font-weight: bold;">
                                                @error('moto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card" style="border: 1px solid #95a5a6; border-radius: 8px; overflow: hidden;">
                                        <div style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; padding: 15px;">
                                            <i class="bi bi-bicycle" style="font-size: 24px; margin-right: 10px;"></i>
                                            <strong>Bicicleta</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Tarifa por hora ($)</label>
                                                <input type="number" name="bicicleta" class="form-control @error('bicicleta') is-invalid @enderror" value="{{ $tarifas['bicicleta'] ?? 500 }}" min="100" required style="font-size: 18px; font-weight: bold;">
                                                @error('bicicleta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none; border-radius: 8px; width: 100%; font-weight: bold; padding: 12px;">
                                        <i class="bi bi-check-circle"></i> Guardar cambios
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <a href="{{ route('parqueadero.dashboard') }}" class="btn btn-lg" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; border: none; border-radius: 8px; width: 100%; font-weight: bold; padding: 12px;">
                                        <i class="bi bi-arrow-left"></i> Volver
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="alert alert-warning" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Acceso denegado</strong> - Solo administradores pueden acceder a esta sección.
            </div>
        @endrole
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.getElementById('menuParqueadero').classList.add('menu-open');
    </script>
@endpush
