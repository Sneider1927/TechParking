@extends('plantilla.app')

@section('contenido')
<div class="app-content">
    <div class="container-fluid">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); padding: 20px; border-radius: 10px; color: white; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h1 style="margin: 0; font-size: 28px;">
                <i class="bi bi-dash-circle" style="font-size: 35px; margin-right: 15px;"></i>Registrar Salida de Vehículo
            </h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Seleccione el vehículo que desea registrar salida</p>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background-color: #ecf0f1; padding: 20px; border-bottom: 3px solid #f39c12;">
                        <h5 style="margin: 0; color: #2c3e50; font-weight: bold;">
                            <i class="bi bi-list"></i> Vehículos Esperando Salida ({{ count($vehiculosActivos) }})
                        </h5>
                    </div>
                    
                    <div class="card-body">
                        @if (Session::has('error'))
                            <div class="alert alert-warning alert-dismissible fade show" style="background-color: #fff3cd; border-color: #f39c12; color: #8a6d3b;">
                                <i class="bi bi-info-circle-fill"></i> {{ Session::get('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover" style="margin: 0;">
                                <thead style="background-color: #ecf0f1;">
                                    <tr>
                                        <th style="color: #2c3e50; font-weight: bold;">Placa</th>
                                        <th style="color: #2c3e50; font-weight: bold;">Tipo Vehículo</th>
                                        <th style="color: #2c3e50; font-weight: bold;">Hora Entrada</th>
                                        <th style="color: #2c3e50; font-weight: bold;">Tiempo Estancia</th>
                                        <th style="color: #2c3e50; font-weight: bold;">Tarifa/Hora</th>
                                        <th style="color: #2c3e50; font-weight: bold;">Estimado a Cobrar</th>
                                        <th style="color: #2c3e50; font-weight: bold;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($vehiculosActivos as $registro)
                                        <tr style="border-color: #ecf0f1;">
                                            <td><strong style="color: #2c3e50; font-size: 16px;">{{ $registro->vehiculo->placa }}</strong></td>
                                            <td>
                                                <span class="badge {{ $registro->vehiculo->tipo === 'carro' ? 'bg-primary' : ($registro->vehiculo->tipo === 'moto' ? 'bg-warning' : 'bg-secondary') }}" style="padding: 8px 12px; font-size: 12px; border-radius: 20px; font-weight: bold; color: white;">
                                                    {{ ucfirst($registro->vehiculo->tipo) }}
                                                </span>
                                            </td>
                                            <td><small style="color: #7f8c8d;">{{ $registro->hora_entrada->format('d/m/Y H:i:s') }}</small></td>
                                            <td>
                                                <small style="font-weight: bold; color: #e74c3c;">
                                                    {{ $registro->hora_entrada->diffForHumans(now(), ['parts' => 2]) }}
                                                </small>
                                            </td>
                                            <td>
                                                @php
                                                    $tarifas = config('tarifas');
                                                    $tarifa = $tarifas[$registro->vehiculo->tipo] ?? 3000;
                                                @endphp
                                                <span class="badge" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 6px 12px;">${{ number_format($tarifa, 0, ',', '.') }}</span>
                                            </td>
                                            <td>
                                                <strong style="color: #e74c3c; font-size: 14px;">${{ number_format($registro->calcularEstimado(), 0, ',', '.') }}</strong>
                                            </td>
                                            <td>
                                                <form action="{{ route('parqueadero.salida.registrar') }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <input type="hidden" name="registro_id" value="{{ $registro->id }}">
                                                    <button type="submit" class="btn btn-sm" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; border-radius: 5px; padding: 8px 16px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                                        <i class="bi bi-dash-circle"></i> Salida
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center" style="padding: 50px; color: #95a5a6;">
                                                <i class="bi bi-inbox" style="font-size: 40px; margin-right: 10px;"></i>
                                                <br><strong>No hay vehículos en el parqueadero</strong>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div style="background-color: #ecf0f1; padding: 15px 20px; border-top: 1px solid #bdc3c7;">
                        <a href="{{ route('parqueadero.dashboard') }}" class="btn" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; border: none; border-radius: 5px; padding: 10px 20px;">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
