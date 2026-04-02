@extends('plantilla.app')

@section('contenido')
<div class="app-content">
    <div class="container-fluid">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); padding: 20px; border-radius: 10px; color: white; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h1 style="margin: 0; font-size: 28px;">
                <i class="bi bi-clock-history" style="font-size: 35px; margin-right: 15px;"></i>Historial de Movimientos
            </h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Registro de todas las transacciones</p>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 20px;">
                    <div style="background-color: #ecf0f1; padding: 20px;">
                        <form action="{{ route('parqueadero.historial') }}" method="GET" class="row g-2">
                            <div class="col-md-8">
                                <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}" style="border: 2px solid #3498db; border-radius: 8px; padding: 10px;">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-block" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border: none; border-radius: 8px; padding: 10px; font-weight: bold; width: 100%;">
                                    <i class="bi bi-search"></i> Filtrar
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('parqueadero.historial') }}" class="btn btn-block" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; border: none; border-radius: 8px; padding: 10px; font-weight: bold; text-decoration: none; width: 100%;">
                                    Limpiar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background-color: #ecf0f1; padding: 20px; border-bottom: 3px solid #3498db;">
                        <h5 style="margin: 0; color: #2c3e50; font-weight: bold;">
                            <i class="bi bi-list"></i> Movimientos ({{ count($registros) }})
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" style="margin: 0;">
                            <thead style="background-color: #ecf0f1;">
                                <tr>
                                    <th style="color: #2c3e50; font-weight: bold;">ID</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Placa</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Tipo</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Entrada</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Salida</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Duración</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Total</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($registros as $registro)
                                    <tr style="border-color: #ecf0f1;">
                                        <td><small style="color: #7f8c8d;">#{{ $registro->id }}</small></td>
                                        <td><strong style="color: #2c3e50; font-size: 15px;">{{ $registro->vehiculo->placa }}</strong></td>
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
                                        <td>
                                            <small style="font-weight: bold; color: #e74c3c;">
                                                {{ $registro->hora_salida->diffInHours($registro->hora_entrada) ?: 1 }} h
                                            </small>
                                        </td>
                                        <td><strong style="color: #27ae60;">${{ number_format($registro->valor_total, 0, ',', '.') }}</strong></td>
                                        <td>
                                            @if($registro->pagado)
                                                <span class="badge" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 6px 10px;">Pagado</span>
                                            @else
                                                <span class="badge" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; padding: 6px 10px;">Pendiente</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center" style="padding: 40px; color: #95a5a6;">
                                            <i class="bi bi-inbox" style="font-size: 40px; margin-right: 10px;"></i>
                                            <br><strong>No hay registros</strong>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div style="background-color: #ecf0f1; padding: 20px; border-top: 1px solid #bdc3c7;">
                        {{ $registros->appends(['fecha' => request('fecha')])->links() }}
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <a href="{{ route('parqueadero.dashboard') }}" class="btn" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; border: none; border-radius: 8px; padding: 10px 20px; font-weight: bold;">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
