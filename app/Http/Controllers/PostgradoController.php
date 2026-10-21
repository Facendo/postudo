<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FiltrablePorCoordinador;
use App\Models\Area;
use App\Models\Cohorte;
use App\Models\Postgrado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostgradoController extends Controller
{
    use FiltrablePorCoordinador;

    /**
     * Muestra una lista de los postgrados.
     */
    public function index()
    {
        // Carga los postgrados con su área para mostrarla en el listado
        // 'menciones' se usa en la fila desplegable de cada postgrado de la tabla.
        // El coordinador de postgrado solo ve los suyos (null = sin filtro).
        $permitidos = $this->idsPostgradosPermitidos(Auth::user());

        $postgrados = Postgrado::with(['area', 'menciones'])
            ->when($permitidos, fn ($query) => $query->whereIn('id_postgrado', $permitidos))
            ->get();

        return view('administrador.gestionpostgrado', compact('postgrados'));
    }

    /**
     * Muestra el formulario para crear un nuevo postgrado.
     * Admite ?area=CODIGO para preseleccionar el área: lo usa el botón
     * «Nuevo Postgrado» de la fila desplegada en la vista de Áreas.
     */
    public function create()
    {
        // Crear postgrados es del administrador/coordinador general.
        if (! $this->puedeGestionarPostgrado(Auth::user(), null)) {
            return redirect()
                ->route('administrador.gestion_postgrado')
                ->with('error', 'Los coordinadores de postgrado no pueden crear postgrados.');
        }

        $areas = Area::orderBy('nombre')->get();

        // Si el código no corresponde a un área existente, el select queda
        // sin marcar; la validación 'exists:area,codigo' de store() lo cubre.
        $areaPreseleccionada = request()->query('area');

        return view('administrador.registropostgrado', compact('areas', 'areaPreseleccionada'));
    }

    /**
     * Almacena un nuevo postgrado en la base de datos.
     */
    public function store(Request $request)
    {
        // Crear postgrados es del administrador/coordinador general.
        if (! $this->puedeGestionarPostgrado(Auth::user(), null)) {
            return redirect()
                ->route('administrador.gestion_postgrado')
                ->with('error', 'Los coordinadores de postgrado no pueden crear postgrados.');
        }

        $request->validate([
            'Id_postgrado' => 'required|string|max:255|unique:postgrado,id_postgrado',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'duracion' => 'required|string|max:255',
            'nro_de_cohortes' => 'required|integer|min:1',
            'codigo_area' => 'required|string|exists:area,codigo',
        ]);

        $postgrado = new Postgrado;
        $postgrado->id_postgrado = $request->Id_postgrado;
        $postgrado->codigo_area = $request->codigo_area;
        $postgrado->nombre = $request->nombre;
        $postgrado->descripcion = $request->descripcion;
        $postgrado->duracion = $request->duracion;
        $postgrado->nro_cohortes = $request->nro_de_cohortes;
        $postgrado->save();

        // El coordinador vuelve a la vista de Áreas (su punto de partida);
        // el administrador no puede ver esa ruta, así que vuelve a su listado.
        $destino = Auth::user()->rol === 'coordinador_general'
            ? 'administrador.creacion.index'
            : 'administrador.gestion_postgrado';

        return redirect()->route($destino)->with('success', 'Postgrado registrado exitosamente.');
    }

    /**
     * Muestra los detalles de un postgrado: sus menciones y sus cohortes.
     */
    public function showdetalles(string $id)
    {
        // El coordinador de postgrado solo entra a los detalles de SUS postgrados.
        if (! $this->puedeGestionarPostgrado(Auth::user(), $id)) {
            return redirect()
                ->route('administrador.gestion_postgrado')
                ->with('error', 'Este postgrado no está asignado a tu cuenta.');
        }

        $postgrado = Postgrado::with('menciones')->findOrFail($id);
        $cohortes = Cohorte::where('codigo_postgrado', $id)->get();

        return view('administrador.detallespostgrado', compact('postgrado', 'cohortes'));
    }

    /**
     * Muestra el formulario para editar un postgrado específico.
     */
    public function edit(string $id)
    {
        // Editar el postgrado mismo es del administrador/coordinador general.
        if (! $this->puedeGestionarPostgrado(Auth::user(), $id)) {
            return redirect()
                ->route('administrador.gestion_postgrado')
                ->with('error', 'Los coordinadores de postgrado no pueden editar el postgrado.');
        }

        $postgrado = Postgrado::findOrFail($id);
        $areas = Area::orderBy('nombre')->get();

        return view('administrador.editarpostgrado', compact('postgrado', 'areas'));
    }

    /**
     * Actualiza un postgrado específico en la base de datos.
     * El ID no es editable porque las cohortes apuntan a él.
     */
    public function update(Request $request, string $id)
    {
        // Editar el postgrado mismo es del administrador/coordinador general.
        if (! $this->puedeGestionarPostgrado(Auth::user(), $id)) {
            return redirect()
                ->route('administrador.gestion_postgrado')
                ->with('error', 'Los coordinadores de postgrado no pueden editar el postgrado.');
        }

        $postgrado = Postgrado::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'duracion' => 'required|string|max:255',
            'codigo_area' => 'required|string|exists:area,codigo',
        ]);

        $postgrado->nombre = $request->nombre;
        $postgrado->descripcion = $request->descripcion;
        $postgrado->duracion = $request->duracion;
        $postgrado->codigo_area = $request->codigo_area;
        $postgrado->save();

        return redirect()->route('administrador.gestion_postgrado')->with('success', 'Postgrado actualizado exitosamente.');
    }

    /**
     * Devuelve en JSON todo lo que se borrará en cascada si se elimina el
     * postgrado. Los estudiantes no se borran: quedan sin mención asignada.
     */
    public function impacto(string $id)
    {
        // Solo el admin/coordinador general consulta el impacto de borrado.
        if (! $this->puedeGestionarPostgrado(Auth::user(), $id)) {
            return redirect()
                ->route('administrador.gestion_postgrado')
                ->with('error', 'Este postgrado no está asignado a tu cuenta.');
        }

        $idsMenciones = DB::table('mencion')
            ->where('id_postgrado', $id)
            ->pluck('id_mencion');

        $idsCohortes = DB::table('cohorte')
            ->where('codigo_postgrado', $id)
            ->pluck('codigo_cohorte');

        $idsMaterias = DB::table('materia')
            ->whereIn('codigo_cohorte', $idsCohortes)
            ->pluck('codigo_materia');

        $idsSecciones = DB::table('seccion')
            ->whereIn('codigo_materia', $idsMaterias)
            ->pluck('nro_seccion');

        return response()->json([
            'menciones' => $idsMenciones->count(),
            'cohortes' => $idsCohortes->count(),
            'materias' => $idsMaterias->count(),
            'secciones' => $idsSecciones->count(),
            'listados' => DB::table('listado')->whereIn('nro_seccion', $idsSecciones)->count(),
            'estudiantes_desasignados' => DB::table('estudiantes')->whereIn('codigo_mencion', $idsMenciones)->count(),
        ]);
    }

    /**
     * Catálogo público: Áreas -> Postgrados -> Menciones.
     */
    public function portal()
    {
        $areas = Area::with('postgrados.menciones')->orderBy('nombre')->get();

        return view('postgrado.index', compact('areas'));
    }

    /**
     * Elimina un postgrado específico (la confirmación con impacto se hace
     * desde el listado antes de enviar la petición).
     */
    public function destroy(string $id)
    {
        // Borrar postgrados es del administrador/coordinador general.
        if (! $this->puedeGestionarPostgrado(Auth::user(), $id)) {
            return redirect()
                ->route('administrador.gestion_postgrado')
                ->with('error', 'Los coordinadores de postgrado no pueden eliminar postgrados.');
        }

        $postgrado = Postgrado::find($id);
        $postgrado->delete();

        return redirect()->route('administrador.gestion_postgrado')->with('success', 'Postgrado eliminado exitosamente, junto con sus menciones y cohortes.');
    }
}
