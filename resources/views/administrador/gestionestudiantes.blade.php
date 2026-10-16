<x-layout title='PostUDO || Gestión de Estudiantes'>
    <section class="main-content-section page-content">

        {{-- ===== PANEL GLASS CONTENEDOR ===== --}}
        <div class="panel-glass">
            <div class="panel-glass-header">
                <h2 class="panel-glass-title">Listado de Estudiantes</h2>
                <a href="{{ route('registro_estudiante.index') }}" class="btn-glass btn-glass-acento">
                    <i class="fa-solid fa-plus"></i> Nuevo Estudiante
                </a>
            </div>

            <table class="tabla-glass">
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Postgrado</th>
                        <th>Mención</th>
                        <th>Correo</th>
                        <th>Edad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($estudiantes as $estudiante)
                        <tr>
                            <td>{{ $estudiante->cedula }}</td>
                            <td>{{ $estudiante->nombre }}</td>
                            <td>{{ $estudiante->apellido }}</td>
                            <td>{{ $estudiante->carrera }}</td>
                            <td>{{ $estudiante->especialidad }}</td>
                            <td>{{ $estudiante->correo }}</td>
                            <td>{{ $estudiante->edad }}</td>

                            <td class="table-actions">
                                <div class="acciones-glass">
                                    {{-- Botón de Editar --}}
                                    <a href="{{ route('administrador.gestion_estudiante.edit', $estudiante) }}"
                                        class="btn-icono-glass" title="Editar">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    {{-- Botón de Eliminar (usando un formulario para solicitudes DELETE) --}}
                                    <form action="{{ route('administrador.gestion_estudiante.destroy', $estudiante) }}"
                                        method="POST" class="inline-form"
                                        onsubmit="return confirm('¿Está seguro de eliminar este estudiante?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icono-glass peligro" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="tabla-glass-vacio">No hay estudiantes registrados.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </section>

    <style>
        /* ===== PANEL GLASS (estética del programa: translúcido + blur + acento rojo) ===== */
        .page-content .panel-glass {
            width: 95%;
            max-width: 1200px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px 35px 25px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.04);
            animation: fadeInSlideUp 0.6s ease-out forwards;
        }

        /* Encabezado: título + botón principal */
        .panel-glass-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding-bottom: 22px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .panel-glass-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.9rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            letter-spacing: 0.2px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.25);
        }

        /* Botón glass con acento rojo al hover (consistente con admin-card) */
        .btn-glass {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-glass-acento:hover {
            background: rgba(255, 77, 77, 0.15);
            border-color: rgba(255, 77, 77, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(204, 0, 0, 0.25);
        }

        /* ===== TABLA GLASS ===== */
        .tabla-glass {
            width: 100%;
            margin-top: 22px;
            border-collapse: separate;
            border-spacing: 0;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 15px;
            overflow: hidden;
            font-family: 'Outfit', sans-serif;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .tabla-glass thead {
            background: rgba(255, 255, 255, 0.07);
        }

        .tabla-glass th {
            padding: 16px 20px;
            text-align: left;
            font-size: 0.82em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .tabla-glass tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            transition: background-color 0.25s ease;
        }

        .tabla-glass tbody tr:last-child {
            border-bottom: none;
        }

        .tabla-glass tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
            cursor: default;
        }

        .tabla-glass td {
            padding: 13px 20px;
            font-size: 0.95em;
            color: rgba(255, 255, 255, 0.9);
            vertical-align: middle;
        }

        /* La última columna (acciones) no hereda el estilo tenue global */
        .tabla-glass td:last-child {
            font-size: 0.95em;
            color: rgba(255, 255, 255, 0.9);
        }

        .tabla-glass-vacio {
            text-align: center;
            padding: 40px 20px !important;
            color: rgba(255, 255, 255, 0.4);
            font-style: italic;
        }

        /* ===== BOTONES DE ACCIÓN (iconos circulares glass) ===== */
        .acciones-glass {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-icono-glass {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.82rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .btn-icono-glass:hover {
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(255, 255, 255, 0.3);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .btn-icono-glass.peligro:hover {
            background: rgba(255, 77, 77, 0.18);
            border-color: rgba(255, 77, 77, 0.5);
            color: #ff6b6b;
        }

        .inline-form {
            display: inline;
        }

        /* En móvil, los botones de acción no deben cortarse */
        @media screen and (max-width: 768px) {
            .page-content .panel-glass {
                width: 98%;
                padding: 20px 15px;
            }

            .panel-glass-header {
                justify-content: center;
                text-align: center;
            }

            .tabla-glass td .acciones-glass {
                flex-direction: row;
            }
        }
    </style>
</x-layout>
