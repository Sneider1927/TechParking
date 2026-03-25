@extends('plantilla.app')

@section('contenido')
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 25px;">
                        <h2 style="margin: 0; font-size: 28px;">
                            <i class="bi bi-receipt" style="font-size: 35px; margin-right: 10px;"></i>Tech Parking - Factura de Pago
                        </h2>
                    </div>
                    <div class="card-body" style="padding: 30px;">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div style="padding: 15px; background-color: #ecf0f1; border-radius: 8px;">
                                    <h5 style="color: #2c3e50; margin-bottom: 15px; font-weight: bold;">
                                        <i class="bi bi-car-front-fill" style="color: #3498db; margin-right: 8px;"></i>Información del Vehículo
                                    </h5>
                                    <p style="margin: 8px 0;">
                                        @php
                                            $tipoVehiculo = $registro->vehiculo->tipo;
                                            $tarifaVehiculo = config('tarifas')[$tipoVehiculo] ?? 3000;
                                        @endphp
                                        <strong style="color: #2c3e50;">Placa:</strong> <span style="color: #e74c3c; font-weight: bold; font-size: 16px;">{{ $registro->vehiculo->placa }}</span><br>
                                        <strong style="color: #2c3e50;">Tipo:</strong> <span style="color: #3498db;">{{ ucfirst($tipoVehiculo) }}</span><br>
                                        <strong style="color: #2c3e50;">Tarifa:</strong> <span style="color: #f39c12;">${{ number_format($tarifaVehiculo, 0, ',', '.') }} por hora</span><br>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="padding: 15px; background-color: #ecf0f1; border-radius: 8px;">
                                    <h5 style="color: #2c3e50; margin-bottom: 15px; font-weight: bold;">
                                        <i class="bi bi-clock" style="color: #f39c12; margin-right: 8px;"></i>Horarios
                                    </h5>
                                    <p style="margin: 8px 0;">
                                        <strong style="color: #2c3e50;">Entrada:</strong> <span style="color: #16a085;">{{ $registro->hora_entrada->format('d/m/Y H:i:s') }}</span><br>
                                        <strong style="color: #2c3e50;">Salida:</strong> <span style="color: #e74c3c;">{{ $registro->hora_salida->format('d/m/Y H:i:s') }}</span><br>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr style="border-color: #ecf0f1; margin: 30px 0;">

                        <div class="row mb-4">
                            <div class="col-md-6 offset-md-3">
                                <div style="padding: 20px; background: linear-gradient(135deg, #f8f9fa 0%, #ecf0f1 100%); border-radius: 8px; border: 2px solid #27ae60;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #bdc3c7;">
                                        <span style="color: #2c3e50; font-weight: bold;">Horas Estancia</span>
                                        @php
                                            $horas = ceil(abs($registro->hora_entrada->floatDiffInHours($registro->hora_salida)));
                                            $horas = max(1, $horas);
                                            $tarifa = config('tarifas')[$registro->vehiculo->tipo] ?? 3000;
                                        @endphp
                                        <span style="color: #3498db; font-weight: bold;">{{ $horas }} horas</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #bdc3c7;">
                                        <span style="color: #2c3e50; font-weight: bold;">Tarifa por Hora</span>
                                        <span style="color: #f39c12; font-weight: bold;">${{ number_format($tarifa, 0, ',', '.') }}</span>
                                    </div>
                                    @php
                                        $totalPagar = $registro->valor_total && $registro->valor_total > 0 ? $registro->valor_total : $registro->calcularValor();
                                    @endphp
                                    <div style="display: flex; justify-content: space-between; background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 15px; border-radius: 8px; margin-top: 15px;">
                                        <h4 style="margin: 0; font-size: 18px;">TOTAL A PAGAR</h4>
                                        <h4 style="margin: 0; font-size: 24px;">${{ number_format($totalPagar, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info" style="background-color: #d1ecf1; border-color: #3498db; color: #0c5460;">
                            <i class="bi bi-info-circle" style="margin-right: 8px;"></i> 
                            <strong>Nota:</strong> El pago mínimo es de 1 hora aunque haya permanecido menos tiempo.
                        </div>

                        <!-- Botones de Acciones -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <a href="{{ route('parqueadero.pdf', $registro->id) }}" class="btn btn-lg d-grid" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; border-radius: 8px; padding: 12px; font-weight: bold; text-decoration: none;">
                                    <i class="bi bi-file-pdf"></i> Descargar PDF
                                </a>
                            </div>
                        </div>

                        @if (!$registro->pagado)
                            <div class="d-grid gap-2">
                                <form action="{{ route('parqueadero.pago', $registro->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none; border-radius: 8px; padding: 14px; font-weight: bold; font-size: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                        <i class="bi bi-check-circle"></i> Confirmar Pago
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-success" style="background-color: #d4edda; border-color: #27ae60; color: #155724;">
                                <i class="bi bi-check-circle-fill"></i> 
                                Pago completado el {{ $registro->updated_at->format('d/m/Y H:i:s') }}
                            </div>
                            <a href="{{ route('parqueadero.dashboard') }}" class="btn btn-lg d-grid" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border: none; border-radius: 8px; padding: 14px; font-weight: bold; font-size: 16px; text-decoration: none;">
                                Volver al Dashboard
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
