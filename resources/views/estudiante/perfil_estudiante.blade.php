<x-layout title='PostUDO || Mi Perfil'>
    
    <main>
        <div class="main-content-section">
            <h1 class="titulo">Bienvenido, Estudiante</h1>

            @if(session('status'))
                <div class="session-status-message">
                    {{ session('status') }}
                </div>
            @endif

            <div class="profile-section">
                {{-- Replace with actual student's avatar URL from your backend --}}
                <img src="{{ asset($user->foto_perfil) }}" alt="Avatar del Estudiante" class="profile-avatar">
                <h2 class="profile-name">¡Hola, {{$user->name}}</h2> {{-- Dynamic data placeholder (e.g., {{ $student->name }}) --}}
                <p class="profile-role">Estudiante de Carrera</p> {{-- Dynamic data placeholder (e.g., {{ $student->career }}) --}}
                <div class="content_texto_bienvenida">
                    <label>
                        "Tu esfuerzo de hoy es el éxito de mañana."
                    </label>
                </div>
            </div>

           
            <hr class="section-divider">

            <h2 class="titulo sub-titulo">Acciones Rápidas</h2>

            <div class="action-buttons-container">
                <a href="{" class="button_body"> {{-- Example route for creating a new post --}}
                    <i class="fas fa-plus-circle icon-left"></i> Nueva Publicación
                </a>
                <a href="" class="button_body"> {{-- Example route for viewing student's posts --}}
                    <i class="fas fa-list-alt icon-left"></i> Ver Mis Publicaciones
                </a>
                <a href="{{ route('estudiante.perfil.edit') }}" class="button_body"> {{-- Example route for editing profile --}}
                    <i class="fas fa-user-edit icon-left"></i> Editar Perfil
                </a>
                <a href="" class="button_body"> {{-- Example route for general settings --}}
                    <i class="fas fa-cog icon-left"></i> Ajustes de Cuenta
                </a>
            </div>
        </div>
    </main>

</x-layout>