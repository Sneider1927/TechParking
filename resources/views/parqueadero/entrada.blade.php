@extends('plantilla.app')

@section('contenido')
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <!-- Header -->
                <div style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); padding: 20px; border-radius: 10px; color: white; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <h1 style="margin: 0; font-size: 28px;">
                        <i class="bi bi-plus-circle" style="font-size: 35px; margin-right: 15px;"></i>Registrar Entrada
                    </h1>
                    <p style="margin: 10px 0 0 0; opacity: 0.9;">Ingrese la información del vehículo</p>
                </div>

                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div class="card-body" style="padding: 30px;">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" style="background-color: #f8d7da; border-color: #e74c3c; color: #a94442;">
                                <i class="bi bi-exclamation-circle-fill"></i> <strong>Error:</strong>
                                <ul style="margin: 10px 0 0 0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (Session::has('error'))
                            <div class="alert alert-warning alert-dismissible fade show" style="background-color: #fff3cd; border-color: #f39c12; color: #8a6d3b;">
                                <i class="bi bi-info-circle-fill"></i> {{ Session::get('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('parqueadero.entrada.registrar') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="placa" class="form-label" style="font-weight: bold; color: #2c3e50; font-size: 16px;">
                                    <i class="bi bi-car-front-fill" style="color: #3498db; margin-right: 8px;"></i>Placa del Vehículo
                                </label>
                                <input type="text" class="form-control @error('placa') is-invalid @enderror" 
                                    id="placa" name="placa" placeholder="Ej: ABC1234" 
                                    value="{{ old('placa') }}" autofocus style="text-transform: uppercase; font-size: 18px; padding: 12px; border: 2px solid #ecf0f1; border-radius: 8px;">
                                @error('placa')
                                    <small class="text-danger" style="margin-top: 5px; display: block;">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="tipo" class="form-label" style="font-weight: bold; color: #2c3e50; font-size: 16px;">
                                    <i class="bi bi-diagram-3" style="color: #f39c12; margin-right: 8px;"></i>Tipo de Vehículo
                                </label>
                                <select class="form-select @error('tipo') is-invalid @enderror" 
                                    id="tipo" name="tipo" required style="font-size: 16px; padding: 12px; border: 2px solid #ecf0f1; border-radius: 8px;">
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="carro" {{ old('tipo') === 'carro' ? 'selected' : '' }}>
                                        🚗 Carro
                                    </option>
                                    <option value="moto" {{ old('tipo') === 'moto' ? 'selected' : '' }}>
                                        🏍️ Moto
                                    </option>
                                    <option value="bicicleta" {{ old('tipo') === 'bicicleta' ? 'selected' : '' }}>
                                        🚴 Bicicleta
                                    </option>
                                </select>
                                @error('tipo')
                                    <small class="text-danger" style="margin-top: 5px; display: block;">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none; border-radius: 8px; padding: 14px; font-weight: bold; font-size: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                    <i class="bi bi-check-circle"></i> Registrar Entrada
                                </button>
                                <a href="{{ route('parqueadero.dashboard') }}" class="btn btn-lg" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; border: none; border-radius: 8px; padding: 14px; font-weight: bold; font-size: 16px; text-decoration: none; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                    <i class="bi bi-arrow-left"></i> Volver
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
