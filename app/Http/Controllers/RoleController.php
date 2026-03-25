<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RoleController extends Controller
{
    use AuthorizesRequests;

    private function authorizeAdministrador()
    {
        if (!Auth::check() || !Auth::user()->hasRole('administrador')) {
            abort(403, 'No autorizado.');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorizeAdministrador();

        $texto = $request->input('texto'); // variable del texto de busqueda
        $registros = Role::with('permissions')->where('name', 'like', "%{$texto}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        $permissions = Permission::all();

        return view('role.index', compact('registros', 'texto', 'permissions'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeAdministrador();

        $permissions = Permission::all();
        return view('role.action', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAdministrador();

        $request->validate([
            'name' => 'required|unique:roles,name',
            'custom_name' => 'nullable|string',
            'description' => 'nullable|string',
            'permissions' => 'required|array',
        ]);
        $registro = Role::create([
            'name' => $request->name,
            'custom_name' => $request->custom_name,
            'description' => $request->description,
            'active' => $request->has('active') ? 1 : 0,
        ]);
        $registro->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('mensaje', 'Rol '.$request->custom_name.' creado satisfactoriamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $this->authorizeAdministrador();

        $permissions = Permission::all();
        return view('role.action', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $this->authorizeAdministrador();

        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'custom_name' => 'nullable|string',
            'description' => 'nullable|string',
            'permissions' => 'required|array',
        ]);
        $role->update([
            'name' => $request->name,
            'custom_name' => $request->custom_name,
            'description' => $request->description,
            'active' => $request->has('active') ? 1 : 0,
        ]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('roles.index')->with('mensaje', 'Rol '.$request->custom_name. '  actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->authorizeAdministrador();

        $role->delete();

        return redirect()->route('roles.index')->with('mensaje', $role->name.' eliminado satisfactoriamente.');
    }

    /**
     * Toggle active status of a role
     */
    public function toggleActive(Role $role)
    {
        $this->authorizeAdministrador();

        $role->update(['active' => !$role->active]);

        $status = $role->active ? 'activado' : 'desactivado';
        return redirect()->route('roles.index')->with('mensaje', 'Rol '.$role->name.' '.$status.' correctamente');
    }
}
