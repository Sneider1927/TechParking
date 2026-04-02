@extends('plantilla.app')
@section('contenido')
    <div class="app-content">
        <div class="container-fluid">
            <!-- Mensaje de Bienvenida -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); padding: 30px; border-radius: 10px; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        <h1 style="margin: 0; font-size: 28px; font-weight: bold;">
                            <i class="bi bi-house-door" style="margin-right: 10px;"></i>
                            Bienvenido, {{ Auth::user()->name }}
                        </h1>
                        <p style="margin: 5px 0 0 0; opacity: 0.9;">{{ now()->format('l, d \\d\\e F \\d\\e Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Alertas -->
            @if (Session::has('mensaje'))
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-dismissible fade show" style="background: linear-gradient(135deg, rgba(39, 174, 96, 0.1) 0%, rgba(34, 153, 84, 0.1) 100%); border: 2px solid #27ae60; color: #27ae60;">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ Session::get('mensaje') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            @endif

            @if (Session::has('error'))
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-dismissible fade show" style="background: linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(192, 57, 43, 0.1) 100%); border: 2px solid #e74c3c; color: #e74c3c;">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            {{ Session::get('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            @endif


            <!-- Módulos Principales -->
            <div class="row">
                <!-- Parqueadero -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <a href="{{route('parqueadero.dashboard')}}" style="text-decoration: none; color: inherit;">
                        <div style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); padding: 25px; border-radius: 10px; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;" 
                             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 15px rgba(0,0,0,0.2)'"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.1)'">
                            <div style="font-size: 40px; margin-bottom: 10px;">
                                <i class="bi bi-car-front"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 20px; font-weight: bold;">Parqueadero</h3>
                            <p style="margin: 8px 0 0 0; opacity: 0.9;">Gestiona entrada, salida y reportes</p>
                        </div>
                    </a>
                </div>

            </div>

            <!-- Accesos Rápidos -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h4 style="font-weight: bold; color: #2c3e50; margin-bottom: 20px;">
                        <i class="bi bi-lightning-fill" style="color: #f39c12; margin-right: 10px;"></i>
                        Accesos Rápidos
                    </h4>
                </div>
            </div>

            <div class="row">
                <!-- Entrada de Vehículos -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <a href="{{route('parqueadero.entrada')}}" class="btn" style="width: 100%; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border: none; border-radius: 8px; padding: 15px; font-weight: 600; text-align: left;">
                        <i class="bi bi-box-arrow-in-right"></i> Entrada de Vehículos
                    </a>
                </div>

                <!-- Salida de Vehículos -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <a href="{{route('parqueadero.salida')}}" class="btn" style="width: 100%; background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none; border-radius: 8px; padding: 15px; font-weight: 600; text-align: left;">
                        <i class="bi bi-box-arrow-out-left"></i> Salida de Vehículos
                    </a>
                </div>

                <!-- Historial -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <a href="{{route('parqueadero.historial')}}" class="btn" style="width: 100%; background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%); color: white; border: none; border-radius: 8px; padding: 15px; font-weight: 600; text-align: left;">
                        <i class="bi bi-clock-history"></i> Historial
                    </a>
                </div>

                <!-- Reportes -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <a href="{{route('parqueadero.reportes')}}" class="btn" style="width: 100%; background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%); color: white; border: none; border-radius: 8px; padding: 15px; font-weight: 600; text-align: left;">
                        <i class="bi bi-bar-chart"></i> Reportes
                    </a>
                </div>
            </div>

            @if (Session::has('mensaje'))
                <div class="row mt-4 mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-dismissible fade show" style="background: linear-gradient(135deg, rgba(39, 174, 96, 0.1) 0%, rgba(34, 153, 84, 0.1) 100%); border: 2px solid #27ae60; color: #27ae60;">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ Session::get('mensaje') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
         document.getElementById('menuDashboard').classList.add('active');
    </script>
@endpush
