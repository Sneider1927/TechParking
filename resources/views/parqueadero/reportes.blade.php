@extends('plantilla.app')

@section('contenido')
<div class="app-content">
    <div class="container-fluid">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); padding: 20px; border-radius: 10px; color: white; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h1 style="margin: 0; font-size: 28px;">
                <i class="bi bi-bar-chart" style="font-size: 35px; margin-right: 15px;"></i>Reportes de Ingresos
            </h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Análisis detallado de ganancias y movimientos</p>
        </div>

        <!-- Filtro de Fechas -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background-color: #ecf0f1; padding: 20px;">
                        <form action="{{ route('parqueadero.reportes') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" style="font-weight: bold; color: #2c3e50;">Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control" style="border: 2px solid #9b59b6; border-radius: 8px; padding: 10px;"
                                    value="{{ request('fecha_inicio', $fechaInicio->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" style="font-weight: bold; color: #2c3e50;">Fecha Fin</label>
                                <input type="date" name="fecha_fin" class="form-control" style="border: 2px solid #9b59b6; border-radius: 8px; padding: 10px;"
                                    value="{{ request('fecha_fin', $fechaFin->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-block" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); color: white; border: none; border-radius: 8px; padding: 10px; font-weight: bold; width: 100%;">
                                    <i class="bi bi-search"></i> Generar Reporte
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Principales -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 20px;">
                        <h5 style="margin: 0; opacity: 0.9;">
                            <i class="bi bi-currency-dollar"></i> Total Ingresos
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 style="color: #27ae60; font-weight: bold; margin: 0;">${{ number_format($totalIngresos, 0, ',', '.') }}</h2>
                        <small class="text-muted">En el período</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; padding: 20px;">
                        <h5 style="margin: 0; opacity: 0.9;">
                            <i class="bi bi-car-front-fill"></i> Total Vehículos
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 style="color: #3498db; font-weight: bold; margin: 0;">{{ $totalVehículos }}</h2>
                        <small class="text-muted">Movimientos registrados</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; padding: 20px;">
                        <h5 style="margin: 0; opacity: 0.9;">
                            <i class="bi bi-percent"></i> Promedio
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 style="color: #f39c12; font-weight: bold; margin: 0;">
                            @if($totalVehículos > 0)
                                ${{ number_format($totalIngresos / $totalVehículos, 0) }}
                            @else
                                $0
                            @endif
                        </h2>
                        <small class="text-muted">Por vehículo</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; padding: 20px;">
                        <h5 style="margin: 0; opacity: 0.9;">
                            <i class="bi bi-calendar-range"></i> Período
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <h5 style="color: #e74c3c; font-weight: bold; margin: 0;">{{ $fechaInicio->format('d/m') }} - {{ $fechaFin->format('d/m') }}</h5>
                        <small class="text-muted">{{ $fechaFin->format('Y') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breakdown por tipo de vehículo -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; padding: 15px;">
                        <h5 style="margin: 0;">🚗 Carros</h5>
                    </div>
                    <div class="card-body">
                        <p style="margin: 10px 0; color: #3498db;"><strong style="font-size: 20px;">{{ $totalCarros }}</strong> vehículos</p>
                        <small class="text-muted">
                            Ingresos: <strong style="color: #27ae60;">${{ number_format($ingresos->where('vehiculo.tipo', 'carro')->sum('valor_total'), 0) }}</strong>
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; padding: 15px;">
                        <h5 style="margin: 0;">🏍️ Motos</h5>
                    </div>
                    <div class="card-body">
                        <p style="margin: 10px 0; color: #f39c12;"><strong style="font-size: 20px;">{{ $totalMotos }}</strong> vehículos</p>
                        <small class="text-muted">
                            Ingresos: <strong style="color: #27ae60;">${{ number_format($ingresos->where('vehiculo.tipo', 'moto')->sum('valor_total'), 0) }}</strong>
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; padding: 15px;">
                        <h5 style="margin: 0;">🚴 Bicicletas</h5>
                    </div>
                    <div class="card-body">
                        <p style="margin: 10px 0; color: #95a5a6;"><strong style="font-size: 20px;">{{ $totalBicicletas }}</strong> vehículos</p>
                        <small class="text-muted">
                            Ingresos: <strong style="color: #27ae60;">${{ number_format($ingresos->where('vehiculo.tipo', 'bicicleta')->sum('valor_total'), 0) }}</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla Detallada -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background-color: #ecf0f1; padding: 20px; border-bottom: 3px solid #9b59b6;">
                        <h5 style="margin: 0; color: #2c3e50; font-weight: bold;">
                            <i class="bi bi-list"></i> Detalle de Movimientos
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" style="margin: 0;">
                            <thead style="background-color: #ecf0f1;">
                                <tr>
                                    <th style="color: #2c3e50; font-weight: bold;">Placa</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Tipo</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Entrada</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Salida</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Horas</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ingresos as $registro)
                                    <tr style="border-color: #ecf0f1;">
                                        <td><strong style="color: #2c3e50;">{{ $registro->vehiculo->placa }}</strong></td>
                                        <td>
                                            <span class="badge" style="padding: 6px 10px; font-size: 11px; border-radius: 15px; font-weight: bold;
                                                @if($registro->vehiculo->tipo === 'carro') background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                                                @elseif($registro->vehiculo->tipo === 'moto') background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
                                                @else background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); @endif color: white;">
                                                {{ ucfirst($registro->vehiculo->tipo) }}
                                            </span>
                                        </td>
                                        <td><small style="color: #7f8c8d;">{{ $registro->hora_entrada->format('d/m H:i') }}</small></td>
                                        <td><small style="color: #7f8c8d;">{{ $registro->hora_salida->format('d/m H:i') }}</small></td>
                                        <td><small style="font-weight: bold;">{{ $registro->hora_salida->diffInHours($registro->hora_entrada) ?: 1 }}</small></td>
                                        <td><strong style="color: #27ae60;">${{ number_format($registro->valor_total, 0) }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center" style="padding: 40px; color: #95a5a6;">
                                            <i class="bi bi-inbox" style="font-size: 40px; margin-right: 10px;"></i>
                                            <br><strong>No hay registros en este período</strong>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('parqueadero.dashboard') }}" class="btn" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; border: none; border-radius: 8px; padding: 10px 20px; font-weight: bold;">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
