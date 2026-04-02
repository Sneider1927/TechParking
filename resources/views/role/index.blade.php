@extends('plantilla.app')
@section('contenido')
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4" style="border: none; box-shadow: 0 4px 10px rgba(0,0,0,0.1)">
                        <div style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); padding: 25px; border-radius: 10px 10px 0 0; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1)">
                            <h3 style="margin: 0; font-size: 24px; font-weight: bold; display: flex; align-items: center; gap: 10px">
                                <i class="bi bi-shield-lock" style="font-size: 28px"></i>
                                Gestión de Roles
                            </h3>
                        </div>
                        <div class="card-body">
                            @hasanyrole('administrador|admin')
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                @if (Session::has('mensaje'))
                                    <div class="alert alert-success alert-dismissible fade show mt-2">
                                        {{ Session::get('mensaje') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <h5 style="font-weight: bold; color: #2c3e50;">Gestión de Roles</h5>
                                        <p>Solo los administradores pueden crear y administrar roles.</p>
                                    </div>
                                    <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> Crear nuevo rol
                                    </a>
                                </div>
                            @endhasanyrole

                            @if(!Auth::user()->hasAnyRole(['administrador', 'admin']))
                                <div class="alert alert-warning">Solo los administradores pueden crear roles desde este módulo.</div>
                            @endif

                            <hr>

                            <h5 style="font-weight: bold; color: #2c3e50; margin-top: 30px;">Roles Creados</h5>

                            <div class="mb-3">
                                <form action="{{ route('roles.index') }}" method="GET" class="d-flex">
                                    <input type="text" name="texto" class="form-control me-2" placeholder="Buscar roles..." value="{{ $texto }}" style="max-width: 300px;">
                                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i> Buscar</button>
                                    @if($texto)
                                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary ms-2"><i class="bi bi-x-circle"></i> Limpiar</a>
                                    @endif
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Nombre Visible</th>
                                            <th>Descripción</th>
                                            <th>Permisos</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($registros as $role)
                                            <tr>
                                                <td>{{ $role->id }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td>{{ $role->custom_name ?? 'N/A' }}</td>
                                                <td>{{ $role->description ?? 'Sin descripción' }}</td>
                                                <td>
                                                    @if($role->permissions->count() > 0)
                                                        <small>{{ $role->permissions->pluck('name')->join(', ') }}</small>
                                                    @else
                                                        <span class="text-muted">Sin permisos</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($role->active)
                                                        <span class="badge bg-success">Activo</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactivo</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil"></i> Editar</a>
                                                    <form action="{{ route('roles.toggleActive', $role->id) }}" method="POST" class="d-inline me-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm {{ $role->active ? 'btn-secondary' : 'btn-success' }}">
                                                            <i class="bi {{ $role->active ? 'bi-toggle-off' : 'bi-toggle-on' }}"></i> {{ $role->active ? 'Desactivar' : 'Activar' }}
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este rol?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No hay roles registrados.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-3">
                                {{ $registros->appends(['texto' => $texto])->links() }}
                            </div>
                        </div>
                        <div class="card-footer clearfix">{{ $registros->appends(['texto' => $texto]) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('menuSeguridad').classList.add('menu-open');
        document.getElementById('itemRole').classList.add('active');
    </script>
@endpush
