@extends('plantilla.app')

@section('contenido')
<div class="app-content">
    <div class="container-fluid">
        <!-- Header Mejorado -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); padding: 30px; border-radius: 10px; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <h1 style="margin: 0; font-size: 32px;">
                        <i class="bi bi-p-circle" style="font-size: 40px; margin-right: 10px;"></i> Panel de Control - Parqueadero
                    </h1>
                    <p style="margin: 10px 0 0 0; opacity: 0.9;">Gestión completa de estacionamiento</p>
                </div>
            </div>
        </div>

        @if (Session::has('mensaje'))
            <div class="alert alert-success alert-dismissible fade show" style="background-color: #d4edda; border-color: #27ae60; color: #155724;">
                <i class="bi bi-check-circle-fill"></i> {{ Session::get('mensaje') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Estadísticas Mejoradas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; padding: 20px;">
                        <h5 style="margin: 0; opacity: 0.9;">
                            <i class="bi bi-car-front-fill"></i> Vehículos Activos
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 style="color: #3498db; font-weight: bold; margin: 0;">{{ count($vehiculosActivos) }}</h2>
                        <small class="text-muted">En el parqueadero</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 20px;">
                        <h5 style="margin: 0; opacity: 0.9;">
                            <i class="bi bi-currency-dollar"></i> Ingresos Hoy
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 style="color: #27ae60; font-weight: bold; margin: 0;">${{ number_format($totalIngresoHoy, 0, ',', '.') }}</h2>
                        <small class="text-muted">Ganancia del día</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; padding: 20px;">
                        <h5 style="margin: 0; opacity: 0.9;">
                            <i class="bi bi-slash-circle-fill"></i> Espacios Libres
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 id="espaciosLibresCounter" style="color: #e74c3c; font-weight: bold; margin: 0;">{{ $espaciosLibres }}/{{ $capacidad }}</h2>
                        <small class="text-muted">Disponibles</small>
                    </div>
                </div>
            </div>
        </div>

        @role('administrador')
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div class="card-body">
                        <h5 class="card-title">Ajustar capacidad total</h5>
                        <form id="capacidadForm" action="{{ route('parqueadero.capacidad.actualizar') }}" method="POST" class="row g-2 align-items-end">
                            @csrf
                            <div class="col-auto">
                                <label for="capacidad" class="form-label">Capacidad máxima</label>
                                <input type="number" id="capacidad" name="capacidad" class="form-control" value="{{ $capacidad }}" min="1" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success" style="margin-top: 24px;" id="btnCapacidad">Actualizar</button>
                            </div>
                        </form>
                        <small class="text-muted">Cambia el límite y el cálculo de espacios libres se ajustará automáticamente.</small>
                        <div id="capacidadOk" class="alert alert-success mt-3" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
        @endrole

        @push('scripts')
        <script>
            document.getElementById('capacidadForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const form = e.target;
                const data = new FormData(form);
                const btn = document.getElementById('btnCapacidad');
                btn.disabled = true;

                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfMeta ? csrfMeta.content : '';
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: data,
                })
                    .then(response => response.json())
                    .then(json => {
                        if (json.success) {
                            document.getElementById('espaciosLibresCounter').textContent = json.espaciosLibres + '/' + json.capacidad;
                            document.getElementById('capacidadOk').innerText = json.mensaje;
                            document.getElementById('capacidadOk').style.display = 'block';
                        } else {
                            document.getElementById('capacidadOk').innerText = 'No se pudo actualizar la capacidad';
                            document.getElementById('capacidadOk').classList.remove('alert-success');
                            document.getElementById('capacidadOk').classList.add('alert-danger');
                            document.getElementById('capacidadOk').style.display = 'block';
                        }
                    })
                    .catch(err => {
                        document.getElementById('capacidadOk').innerText = 'Error al actualizar';
                        document.getElementById('capacidadOk').classList.remove('alert-success');
                        document.getElementById('capacidadOk').classList.add('alert-danger');
                        document.getElementById('capacidadOk').style.display = 'block';
                        console.error(err);
                    })
                    .finally(() => {
                        btn.disabled = false;
                    });
            });
        </script>
        @endpush

        <!-- Botones de Acción Mejorados -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;">
                    <a href="{{ route('parqueadero.entrada') }}" class="btn btn-lg" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none; border-radius: 8px; padding: 15px; text-align: center; text-decoration: none; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s;">
                        <i class="bi bi-plus-circle" style="font-size: 20px;"></i><br> Entrada
                    </a>
                    <a href="{{ route('parqueadero.salida') }}" class="btn btn-lg" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none; border-radius: 8px; padding: 15px; text-align: center; text-decoration: none; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s;">
                        <i class="bi bi-dash-circle" style="font-size: 20px;"></i><br> Salida
                    </a>
                    <a href="{{ route('parqueadero.historial') }}" class="btn btn-lg" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border: none; border-radius: 8px; padding: 15px; text-align: center; text-decoration: none; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s;">
                        <i class="bi bi-clock-history" style="font-size: 20px;"></i><br> Historial
                    </a>
                    <a href="{{ route('parqueadero.reportes') }}" class="btn btn-lg" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); color: white; border: none; border-radius: 8px; padding: 15px; text-align: center; text-decoration: none; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s;">
                        <i class="bi bi-bar-chart" style="font-size: 20px;"></i><br> Reportes
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabla de Vehículos Activos Mejorada -->
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%); color: white; padding: 20px;">
                        <h3 style="margin: 0;">
                            <i class="bi bi-view-list"></i> Vehículos en Parqueadero
                        </h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" style="margin: 0;">
                            <thead style="background-color: #ecf0f1;">
                                <tr>
                                    <th style="color: #2c3e50; font-weight: bold;">Placa</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Tipo</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Hora Entrada</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Tiempo Estancia</th>
                                    <th style="color: #2c3e50; font-weight: bold;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($vehiculosActivos as $registro)
                                    <tr style="border-color: #ecf0f1;">
                                        <td><strong style="color: #2c3e50;">{{ $registro->vehiculo->placa }}</strong></td>
                                        <td>
                                            <span class="badge" style="padding: 8px 12px; font-size: 12px; border-radius: 20px; font-weight: bold;
                                                @if($registro->vehiculo->tipo === 'carro') background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                                                @elseif($registro->vehiculo->tipo === 'moto') background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
                                                @else background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); @endif color: white;">
                                                {{ ucfirst($registro->vehiculo->tipo) }}
                                            </span>
                                        </td>
                                        <td><small>{{ $registro->hora_entrada->format('H:i:s') }}</small></td>
                                        <td><small><strong>{{ $registro->hora_entrada->diffForHumans(now(), ['parts' => 2]) }}</strong></small></td>
                                        <td>
                                            <a href="{{ route('parqueadero.salida') }}" class="btn btn-sm" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; border-radius: 5px; padding: 6px 12px; font-size: 12px;">
                                                Registrar
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center" style="padding: 30px; color: #7f8c8d;">
                                            <i class="bi bi-inbox" style="font-size: 30px; margin-right: 10px;"></i>
                                            <br>No hay vehículos activos
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (hover: hover) {
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
        }
    }
</style>
@endsection
