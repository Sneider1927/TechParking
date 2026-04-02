<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\RegistroParqueadero;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class ParqueaderoController extends Controller
{
    /**
     * Mostrar dashboard principal
     */
    private function getTarifasFromFile()
    {
        $path = config_path('tarifas.php');
        if (file_exists($path)) {
            return include $path;
        }
        return config('tarifas', [
            'carro' => 3000,
            'moto' => 1000,
            'bicicleta' => 500,
            'capacidad' => 50,
        ]);
    }

    public function dashboard()
    {
        $queryActivos = RegistroParqueadero::whereNull('hora_salida')->with('vehiculo');
        $queryIngresos = RegistroParqueadero::whereNotNull('hora_salida');

        $user = Auth::user();
        if (!$user || !$user->hasRole('administrador')) {
            $queryActivos->where('user_id', Auth::id());
            $queryIngresos->where('user_id', Auth::id());
        }

        $vehiculosActivos = $queryActivos->get();
        $totalIngresoHoy = $queryIngresos
            ->whereDate('created_at', today())
            ->sum('valor_total');

        $tarifas = $this->getTarifasFromFile();
        $capacidad = $tarifas['capacidad'] ?? 50;
        $ocupados = count($vehiculosActivos);
        $espaciosLibres = max($capacidad - $ocupados, 0);

        return view('parqueadero.dashboard', compact('vehiculosActivos', 'totalIngresoHoy', 'capacidad', 'ocupados', 'espaciosLibres'));
    }

    public function actualizarCapacidad(Request $request)
    {
        if (!Auth::user()->hasRole('administrador')) {
            abort(403, 'No está autorizado para realizar esta acción');
        }

        $request->validate([
            'capacidad' => 'required|integer|min:1',
        ]);

        $capacidad = (int) $request->input('capacidad');

        $tarifas = $this->getTarifasFromFile();
        $tarifas['capacidad'] = $capacidad;

        $config = "<?php\n\nreturn " . var_export($tarifas, true) . ";\n";
        file_put_contents(config_path('tarifas.php'), $config);

        // Actualiza en runtime sin forzar comandos pesados que causan demora
        config(['tarifas' => $tarifas]);

        $ocupados = RegistroParqueadero::whereNull('hora_salida')->count();
        $espaciosLibres = max($capacidad - $ocupados, 0);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => 'Capacidad actualizada correctamente',
                'capacidad' => $capacidad,
                'espaciosLibres' => $espaciosLibres,
            ]);
        }

        return back()->with('mensaje', 'Capacidad actualizada correctamente');
    }

    /**
     * Mostrar formulario de entrada de vehículo
     */
    public function entrada()
    {
        $tarifas = $this->getTarifasFromFile();
        return view('parqueadero.entrada', compact('tarifas'));
    }

    /**
     * Registrar entrada de vehículo
     */
    public function registrarEntrada(Request $request)
    {
        $request->validate([
            'placa' => 'required|string|max:10',
            'tipo' => 'required|in:carro,moto,bicicleta',
        ], [
            'placa.required' => 'La placa es obligatoria',
            'tipo.required' => 'El tipo de vehículo es obligatorio',
        ]);

        // Verificar si el vehículo ya está en el parqueadero
        $registroActivo = RegistroParqueadero::whereHas('vehiculo', function ($q) use ($request) {
            $q->where('placa', $request->placa);
        })->whereNull('hora_salida');

        if (!Auth::user()->hasRole('administrador')) {
            $registroActivo->where('user_id', Auth::id());
        }

        $registroActivo = $registroActivo->first();

        if ($registroActivo) {
            return back()->with('error', 'Este vehículo ya está en el parqueadero');
        }

        // Verificar capacidad del parqueadero
        $tarifas = $this->getTarifasFromFile();
        $capacidad = $tarifas['capacidad'] ?? 50;
        $vehiculosActivos = RegistroParqueadero::whereNull('hora_salida')->count();

        if ($vehiculosActivos >= $capacidad) {
            return back()->with('error', 'El parqueadero está lleno. Capacidad máxima: ' . $capacidad . ' vehículos');
        }

        // Crear o obtener vehículo
        $vehiculo = Vehiculo::firstOrCreate(
            ['placa' => strtoupper($request->placa)],
            ['tipo' => $request->tipo]
        );

        // Actualizar tipo si cambió
        if ($vehiculo->tipo !== $request->tipo) {
            $vehiculo->update(['tipo' => $request->tipo]);
        }

        // Registrar entrada
        RegistroParqueadero::create([
            'vehiculo_id' => $vehiculo->id,
            'user_id' => Auth::id(),
            'hora_entrada' => Carbon::now(),
        ]);

        return redirect()->route('parqueadero.dashboard')
            ->with('mensaje', 'Entrada registrada correctamente para ' . $request->placa);
    }

    /**
     * Mostrar vehículos activos para salida
     */
    public function salida()
    {
        $query = RegistroParqueadero::whereNull('hora_salida')->with('vehiculo')->orderBy('hora_entrada', 'desc');

        if (!Auth::user()->hasRole('administrador')) {
            $query->where('user_id', Auth::id());
        }

        $vehiculosActivos = $query->get();

        return view('parqueadero.salida', compact('vehiculosActivos'));
    }

    /**
     * Registrar salida de vehículo y calcular cobro
     */
    public function registrarSalida(Request $request)
    {
        $request->validate([
            'registro_id' => 'required|exists:registro_parqueaderos,id',
        ]);

        $registro = RegistroParqueadero::findOrFail($request->registro_id);

        if (!Auth::user()->hasRole('administrador') && $registro->user_id !== Auth::id()) {
            return back()->with('error', 'No está autorizado para completar la salida de este registro');
        }

        if ($registro->hora_salida) {
            return back()->with('error', 'Este registro ya tiene hora de salida');
        }

        $registro->update([
            'hora_salida' => Carbon::now(),
            'valor_total' => $registro->calcularValor(),
        ]);

        return redirect()->route('parqueadero.factura', $registro->id)
            ->with('mensaje', 'Salida registrada. Por favor complete el pago.');
    }

    /**
     * Mostrar factura de salida
     */
    public function factura($registroId)
    {
        $registro = RegistroParqueadero::with('vehiculo')->findOrFail($registroId);

        if (!Auth::user()->hasRole('administrador') && $registro->user_id !== Auth::id()) {
            return back()->with('error', 'No está autorizado para ver esta factura');
        }

        if (!$registro->hora_salida) {
            return back()->with('error', 'Este vehículo no ha registrado salida');
        }

        return view('parqueadero.factura', compact('registro'));
    }

    /**
     * Marcar pago como completado
     */
    public function completarPago($registroId)
    {
        $registro = RegistroParqueadero::findOrFail($registroId);

        if (!Auth::user()->hasRole('administrador') && $registro->user_id !== Auth::id()) {
            return back()->with('error', 'No está autorizado para completar el pago en este registro');
        }

        $registro->update(['pagado' => true]);

        return redirect()->route('parqueadero.dashboard')
            ->with('mensaje', 'Pago completado exitosamente');
    }

    /**
     * Descargar factura en PDF
     */
    public function descargarPDF($registroId)
    {
        $registro = RegistroParqueadero::with('vehiculo')->findOrFail($registroId);

        $pdf = App::make('dompdf.wrapper')->loadView('parqueadero.factura-pdf', compact('registro'))
                    ->setPaper('a4')
                    ->setOption('margin-top', 10)
                    ->setOption('margin-right', 10)
                    ->setOption('margin-bottom', 10)
                    ->setOption('margin-left', 10);

        return $pdf->download('Factura_' . $registro->vehiculo->placa . '_' . $registro->id . '.pdf');
    }

    /**
     * Mostrar historial de movimientos
     */
    public function historial(Request $request)
    {
        $query = RegistroParqueadero::with('vehiculo')->whereNotNull('hora_salida');

        if (!Auth::user()->hasRole('administrador')) {
            $query->where('user_id', Auth::id());
        }

        $registros = $query
            ->when($request->fecha, function ($q) use ($request) {
                $q->whereDate('created_at', $request->fecha);
            })
            ->orderBy('hora_salida', 'desc')
            ->paginate(15);

        return view('parqueadero.historial', compact('registros'));
    }

    /**
     * Reportes de ingresos
     */
    public function reportes(Request $request)
    {
<<<<<<< HEAD
=======
        if (!Auth::user()->hasRole('administrador')) {
            abort(403, 'No está autorizado para acceder a reportes');
        }

>>>>>>> d29908e (actualizando)
        $fechaInicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio) : Carbon::now()->startOfMonth();
        $fechaFin = $request->fecha_fin ? Carbon::parse($request->fecha_fin) : Carbon::now()->endOfMonth();

        $query = RegistroParqueadero::with('vehiculo')
            ->whereNotNull('hora_salida')
            ->whereBetween('hora_salida', [$fechaInicio, $fechaFin]);

<<<<<<< HEAD
        if (!Auth::user()->hasRole('administrador')) {
            $query->where('user_id', Auth::id());
        }

=======
>>>>>>> d29908e (actualizando)
        $ingresos = $query->get();

        $totalIngresos = $ingresos->sum('valor_total');
        $totalVehículos = $ingresos->count();
        $totalCarros = $ingresos->where('vehiculo.tipo', 'carro')->count();
        $totalMotos = $ingresos->where('vehiculo.tipo', 'moto')->count();
        $totalBicicletas = $ingresos->where('vehiculo.tipo', 'bicicleta')->count();

        return view('parqueadero.reportes', compact(
            'ingresos',
            'totalIngresos',
            'totalVehículos',
            'totalCarros',
            'totalMotos',
            'totalBicicletas',
            'fechaInicio',
            'fechaFin'
        ));
    }

    /**
     * Mostrar configuración de tarifas (solo admin)
     */
    public function configurarTarifas()
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('administrador')) {
            abort(403, 'No está autorizado para acceder a esta sección');
        }

        $tarifas = $this->getTarifasFromFile();
        return view('parqueadero.tarifas', compact('tarifas'));
    }

    /**
     * Actualizar tarifas (solo admin)
     */
    public function actualizarTarifas(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('administrador')) {
            abort(403, 'No está autorizado para realizar esta acción');
        }

        $request->validate([
            'carro' => 'required|numeric|min:100',
            'moto' => 'required|numeric|min:100',
            'bicicleta' => 'required|numeric|min:100',
        ]);

        // Actualizar el archivo de configuración
        $tarifasActuales = config('tarifas', []);
        $tarifas = array_merge($tarifasActuales, [
            'carro' => (int) $request->input('carro'),
            'moto' => (int) $request->input('moto'),
            'bicicleta' => (int) $request->input('bicicleta'),
        ]);

        $config = "<?php\n\nreturn " . var_export($tarifas, true) . ";\n";
        file_put_contents(config_path('tarifas.php'), $config);

        // Limpiar cache config para entornos con config:cache, que evita ver cambios hasta próxima solicitud.
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        // Actualiza la configuración en el runtime para el request actual
        config(['tarifas' => $tarifas]);

        // Opcional: claro que esto puede ser lento, pero sólo si tienes cache de configuración activa.
        // Artisan::call('config:clear');
        // Artisan::call('cache:clear');

        return back()->with('mensaje', 'Tarifas actualizadas correctamente');
    }
}

