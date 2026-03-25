<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\PerfilController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ParqueaderoController;
use Spatie\Permission\Models\Permission;
use App\Models\Role;

// Evitar enlace de ruta con clase base Spatie directo para evitar conflicto de tipo
// y permitir que Route Model Binding use App\Models\Role.
Route::model('role', Role::class);

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::resource('usuarios', UserController::class);
    Route::post('usuarios/{id}/assign-role', [UserController::class, 'assignRole'])->name('usuarios.assignRole');
    Route::resource('roles', RoleController::class);
    Route::post('roles/{id}/toggle-active', [RoleController::class, 'toggleActive'])->name('roles.toggleActive');
    Route::resource('productos', ProductoController::class);
    Route::patch('usuarios/{usuario}/toggle', [UserController::class, 'toggleStatus'])->name('usuarios.toggle');
    Route::get('dashboard', function () { return view('dashboard');})->name('dashboard');
    //segunda parte logout
    Route::post('logout', function (){
     Auth::logout();
     return redirect('/login');
    })->name('logout');

    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');

    // Rutas del parqueadero
    Route::get('parqueadero/dashboard', [ParqueaderoController::class, 'dashboard'])->name('parqueadero.dashboard');
    Route::get('parqueadero/entrada', [ParqueaderoController::class, 'entrada'])->name('parqueadero.entrada');
    Route::post('parqueadero/entrada', [ParqueaderoController::class, 'registrarEntrada'])->name('parqueadero.entrada.registrar');
    Route::get('parqueadero/salida', [ParqueaderoController::class, 'salida'])->name('parqueadero.salida');
    Route::post('parqueadero/salida', [ParqueaderoController::class, 'registrarSalida'])->name('parqueadero.salida.registrar');
    Route::get('parqueadero/factura/{registroId}', [ParqueaderoController::class, 'factura'])->name('parqueadero.factura');
    Route::get('parqueadero/descargar-pdf/{registroId}', [ParqueaderoController::class, 'descargarPDF'])->name('parqueadero.pdf');
    Route::post('parqueadero/pago/{registroId}', [ParqueaderoController::class, 'completarPago'])->name('parqueadero.pago');
    Route::get('parqueadero/historial', [ParqueaderoController::class, 'historial'])->name('parqueadero.historial');
    Route::get('parqueadero/reportes', [ParqueaderoController::class, 'reportes'])->name('parqueadero.reportes');
    Route::get('parqueadero/tarifas', [ParqueaderoController::class, 'configurarTarifas'])->name('parqueadero.tarifas');
    Route::post('parqueadero/tarifas', [ParqueaderoController::class, 'actualizarTarifas'])->name('parqueadero.tarifas.actualizar');
    Route::post('parqueadero/capacidad', [ParqueaderoController::class, 'actualizarCapacidad'])->name('parqueadero.capacidad.actualizar');

    // Rutas para crear roles (protegidas por autenticación y solo accesibles para administradores)
    Route::middleware('role:administrador')->group(function () {
        Route::post('/crear-rol-administrador', function () {
            try {
                $administrador = Role::firstOrCreate(['name' => 'administrador']);
                
                $permisosAdmin = [
                    'user-list', 'user-create', 'user-edit', 'user-delete',
                    'rol-list', 'rol-create', 'rol-edit', 'rol-delete',
                    'producto-list', 'producto-create', 'producto-edit', 'producto-delete',
                ];
                
                foreach ($permisosAdmin as $perm) {
                    $permiso = Permission::firstOrCreate(['name' => $perm]);
                    $administrador->givePermissionTo($permiso);
                }
                
                return redirect('/dashboard')->with('mensaje', '✅ Rol Administrador creado exitosamente');
                
            } catch (Exception $e) {
                return back()->with('error', '❌ Error: ' . $e->getMessage());
            }
        });

        Route::post('/crear-rol-usuario', function () {
            try {
                $usuario = Role::firstOrCreate(['name' => 'usuario']);
                
                $permisosUsuario = ['perfil', 'pedido-view', 'pedido-cancel'];
                
                foreach ($permisosUsuario as $perm) {
                    $permiso = Permission::firstOrCreate(['name' => $perm]);
                    $usuario->givePermissionTo($permiso);
                }
                
                return redirect('/dashboard')->with('mensaje', '✅ Rol Usuario creado exitosamente');
                
            } catch (Exception $e) {
                return back()->with('error', '❌ Error: ' . $e->getMessage());
            }
        });

        Route::get('/crear-roles-iniciales', function () {
            try {
                // Crear Rol Administrador
                $administrador = Role::firstOrCreate(['name' => 'administrador']);
                
                $permisosAdmin = [
                    'user-list', 'user-create', 'user-edit', 'user-delete',
                    'rol-list', 'rol-create', 'rol-edit', 'rol-delete',
                    'producto-list', 'producto-create', 'producto-edit', 'producto-delete',
                ];
                
                foreach ($permisosAdmin as $perm) {
                    $permiso = Permission::firstOrCreate(['name' => $perm]);
                    $administrador->givePermissionTo($permiso);
                }
                
                // Crear Rol Usuario
                $usuario = Role::firstOrCreate(['name' => 'usuario']);
                
                $permisosUsuario = ['perfil', 'pedido-view', 'pedido-cancel'];
                
                foreach ($permisosUsuario as $perm) {
                    $permiso = Permission::firstOrCreate(['name' => $perm]);
                    $usuario->givePermissionTo($permiso);
                }
                
                return redirect()->route('roles.index')->with('mensaje', 'Roles creados exitosamente: Administrador y Usuario');
                
            } catch (Exception $e) {
                return back()->with('error', 'Error: ' . $e->getMessage());
            }
        });
    });
});

Route::middleware('guest')->group(function () {
    Route::get('login', function () { return view('autenticacion.login');})->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('registro');
    Route::post('/registro', [RegisterController::class, 'registration'])->name('registro.store');

    Route::get('password/reset', [ResetPasswordController::class, 'showRequestForm'])->name('password.request');
    Route::post('password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.send-link');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
});