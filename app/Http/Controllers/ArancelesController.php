<?php

namespace App\Http\Controllers;

use App\Services\ArancelesService;
use Illuminate\Http\Request;

/**
 * Panel del administrador para actualizar la normativa de aranceles
 * que muestra el modal de la portada (texto crudo en aranceles.json;
 * el parseo al formato visual lo hace ArancelesService al renderizar).
 */
class ArancelesController extends Controller
{
    public function __construct(private readonly ArancelesService $aranceles)
    {
    }

    /**
     * Formulario con el texto vigente (o el por defecto) para editar.
     */
    public function edit()
    {
        return view('administrador.aranceles', [
            'texto' => $this->aranceles->obtenerTexto(),
            'metadatos' => $this->aranceles->metadatos(),
        ]);
    }

    /**
     * Guarda el texto crudo tal como lo pegó el administrador.
     */
    public function update(Request $request)
    {
        $datos = $request->validate([
            'texto' => ['required', 'string', 'min:20', 'max:30000'],
        ]);

        $this->aranceles->guardar($datos['texto'], $request->user());

        return redirect()
            ->route('administrador.aranceles.edit')
            ->with('success', 'Aranceles actualizados: los cambios ya se ven en el modal de la portada.');
    }
}
