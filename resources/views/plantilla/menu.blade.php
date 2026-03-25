<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="{{route('dashboard')}}" class="brand-link d-flex align-items-center gap-2">
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">TechParking</span>
            <!--end::Brand Text-->
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <!-- Panel de Control -->
                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link" id="menuDashboard">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Panel de Control</p>
                    </a>
                </li>

                <!-- Parqueadero -->
                <li class="nav-item" id="menuParqueadero">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-car-front"></i>
                        <p>
                            Parqueadero
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('parqueadero.dashboard')}}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('parqueadero.entrada')}}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Entrada de Vehículos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('parqueadero.salida')}}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Salida de Vehículos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('parqueadero.historial')}}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Historial</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('parqueadero.reportes')}}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Reportes</p>
                            </a>
                        </li>
                        @role('administrador')
                        <li class="nav-item">
                            <a href="{{route('parqueadero.tarifas')}}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Configurar Tarifas</p>
                            </a>
                        </li>
                        @endrole
                    </ul>
                </li>

                <!-- Roles -->
                @role('administrador')
                <li class="nav-item" id="menuSeguridad">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-shield-lock"></i>
                        <p>
                            Seguridad
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('usuarios.index')}}" class="nav-link" id="itemUsuario">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Usuarios</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('roles.index')}}" class="nav-link" id="itemRole">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                <!-- Perfil Usuario -->
                <li class="nav-item">
                    <a href="{{route('perfil.edit')}}" class="nav-link" id="menuPerfil">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>Mi Perfil</p>
                    </a>
                </li>

                <!-- Separador -->
                <li class="nav-header">Sesión</li>

                <!-- Logout -->
                <li class="nav-item">
                    <form action="{{route('logout')}}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                            <i class="nav-icon bi bi-box-arrow-right"></i>
                            <p>Cerrar Sesión</p>
                        </button>
                    </form>
                </li>
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>