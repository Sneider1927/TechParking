<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guardName = 'web';

        // crear roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guardName]);
        $clienteRole = Role::firstOrCreate(['name' => 'cliente', 'guard_name' => $guardName]);
        $estudianteRole = Role::firstOrCreate(['name' => 'estudiante', 'guard_name' => $guardName]);
        $administradorRole = Role::firstOrCreate(['name' => 'administrador', 'guard_name' => $guardName]);
        $usuarioRole = Role::firstOrCreate(['name' => 'usuario', 'guard_name' => $guardName]);

        // Definir permisos
        $adminPermissions = [
            'user-list', 'user-create', 'user-edit', 'user-delete', 'user-activate',
            'rol-list', 'rol-create', 'rol-edit', 'rol-delete',
            'producto-list', 'producto-create', 'producto-edit', 'producto-delete',
            'pedido-list', 'pedido-anulate',
        ];

        $clientePermissions = ['pedido-view', 'pedido-cancel', 'perfil'];
        $estudiantePermissions = ['user-list', 'rol-list', 'perfil'];
        
        $administradorPermissions = [
            'user-list', 'user-create', 'user-edit', 'user-delete',
            'rol-list', 'rol-create', 'rol-edit', 'rol-delete',
            'producto-list', 'producto-create', 'producto-edit', 'producto-delete',
        ];
        
        $usuarioPermissions = ['perfil', 'pedido-view', 'pedido-cancel'];

        // crear y asignar permisos
        foreach ($adminPermissions as $permiso) {
            $permission = Permission::firstOrCreate(['name' => $permiso, 'guard_name' => $guardName]);
            $adminRole->givePermissionTo($permission);
            $administradorRole->givePermissionTo($permission);
        }

        foreach ($clientePermissions as $permiso) {
            $permission = Permission::firstOrCreate(['name' => $permiso, 'guard_name' => $guardName]);
            $clienteRole->givePermissionTo($permission);
        }

        foreach ($estudiantePermissions as $permiso) {
            $permission = Permission::firstOrCreate(['name' => $permiso, 'guard_name' => $guardName]);
            $estudianteRole->givePermissionTo($permission);
        }

        foreach ($administradorPermissions as $permiso) {
            $permission = Permission::firstOrCreate(['name' => $permiso, 'guard_name' => $guardName]);
            $administradorRole->givePermissionTo($permission);
        }

        foreach ($usuarioPermissions as $permiso) {
            $permission = Permission::firstOrCreate(['name' => $permiso, 'guard_name' => $guardName]);
            $usuarioRole->givePermissionTo($permission);
        }

        // Crear usuarios y asignar roles
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@prueba.com'],
            ['name' => 'Admin', 'password' => 'admin123456', 'activo' => 1]
        );

        $adminUser->syncRoles([$adminRole, $administradorRole]);

        $clienteUser = User::updateOrCreate(
            ['email' => 'cliente@prueba.com'],
            ['name' => 'Cliente', 'password' => 'cliente123456', 'activo' => 1]
        );

        $clienteUser->syncRoles([$clienteRole]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

    }
}
