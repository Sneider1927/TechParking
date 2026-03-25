@extends('plantilla.app')

@section('contenido')
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card mb-4" style="border: none; box-shadow: 0 4px 10px rgba(0,0,0,0.1)">

                        <div style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); padding: 25px; border-radius: 10px 10px 0 0; color: white;">
                            <h3 style="margin: 0; font-size: 24px; font-weight: bold; display: flex; align-items: center; gap: 10px;">
                                <i class="bi {{ isset($role) ? 'bi-pencil-square' : 'bi-plus-circle' }}" style="font-size: 28px"></i>
                                {{ isset($role) ? 'Editar Rol' : 'Crear Nuevo Rol' }}
                            </h3>
                        </div>

                        <div class="card-body">
                            <form
                                action="{{ isset($role) && $role ? route('roles.update', ['role' => $role->id]) : route('roles.store') }}"
                                method="POST" id="formRegistroUsuario">
                                @csrf
                                @if (isset($role) && $role)
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <label for="name" class="form-label" style="font-weight: 600; color: #2c3e50; font-size: 16px;">Rol</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $role->name ?? '') }}"
                                            placeholder="Introduce un nombre de rol (p.ej. gerente, supervisor, cliente)"
                                            style="border-radius: 5px; border: 2px solid #ecf0f1; padding: 10px;" required>
                                        @error('name')
                                            <small class="text-danger" style="margin-top: 5px; display: block;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="active" class="form-label" style="font-weight: 600; color: #2c3e50; font-size: 16px;">Estado</label>
                                        <div class="form-check form-switch" style="padding-top: 10px;">
                                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1"
                                                {{ old('active', $role->active ?? 1) ? 'checked' : '' }}
                                                style="width: 50px; height: 25px; cursor: pointer;">
                                            <label class="form-check-label" for="active" style="margin-left: 10px; cursor: pointer; font-weight: 500;">
                                                <span id="statusText" class="{{ old('active', $role->active ?? 1) ? 'text-success' : 'text-danger' }}" style="font-weight: 600;">
                                                    {{ old('active', $role->active ?? 1) ? '✓ Activo' : '✗ Inactivo' }}
                                                </span>
                                            </label>
                                        </div>
                                        <div id="statusMessage" style="margin-top: 10px; padding: 10px; border-radius: 5px; display: none; font-weight: 600;" class="alert">
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <label for="description" class="form-label" style="font-weight: 600; color: #2c3e50; font-size: 16px;">Descripción</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                            id="description" name="description" rows="4"
                                            placeholder="Describe las responsabilidades y funciones de este rol..."
                                            style="border-radius: 5px; border: 2px solid #ecf0f1; padding: 10px; resize: vertical;">{{ old('description', $role->description ?? '') }}</textarea>
                                        @error('description')
                                            <small class="text-danger" style="margin-top: 5px; display: block;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label" style="font-weight: 600; color: #2c3e50; font-size: 16px; margin-bottom: 15px; display: block;">
                                            <i class="bi bi-lock"></i> Asignar Permisos
                                        </label>
                                        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #9b59b6;">
                                            <div class="row">
                                                @foreach ($permissions as $permission)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-check" style="padding: 10px; background: white; border-radius: 5px; transition: background 0.3s;" onmouseover="this.style.background='#ecf0f1'" onmouseout="this.style.background='white'">
                                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                                value="{{ $permission->name }}" id="permiso_{{ $permission->id }}"
                                                                {{ isset($role) && $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                                style="width: 20px; height: 20px; cursor: pointer;">
                                                            <label for="permiso_{{ $permission->id }}" class="form-check-label" style="margin-left: 8px; cursor: pointer; font-weight: 500;">
                                                                <span style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                                                    {{ ucfirst($permission->name) }}
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @error('permissions')
                                            <small class="text-danger" style="margin-top: 10px; display: block;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-top: 30px;">
                                    <a href="{{ route('roles.index') }}" class="btn btn-secondary me-md-2">
                                        <i class="bi bi-x-circle"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Guardar
                                    </button>
                                </div>
                            </form>
                        </div>

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
        
        // Cambiar estado del texto
        document.getElementById('active').addEventListener('change', function() {
            const statusText = document.getElementById('statusText');
            const statusMessage = document.getElementById('statusMessage');
            
            if (this.checked) {
                statusText.textContent = '✓ Activo';
                statusText.style.color = '#27ae60';
                statusMessage.style.display = 'none';
            } else {
                statusText.textContent = '✗ Inactivo';
                statusText.style.color = '#e74c3c';
                
                // Mostrar mensaje cuando se desactiva
                statusMessage.style.display = 'block';
                statusMessage.style.background = 'linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(192, 57, 43, 0.1) 100%)';
                statusMessage.style.border = '2px solid #e74c3c';
                statusMessage.style.color = '#e74c3c';
                statusMessage.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>Inactivo';
            }
        });
    </script>
@endpush
