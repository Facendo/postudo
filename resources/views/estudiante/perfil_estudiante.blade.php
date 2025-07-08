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

            <hr class="section-divider"> {{-- Using a class for the HR for better styling from CSS --}}

            <h2 class="titulo sub-titulo">Tu Progreso en PostUDO</h2> {{-- Added sub-titulo class for specific styling --}}

            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <i class="fas fa-file-alt dashboard-card-icon"></i> {{-- Icon for submitted posts --}}
                    <div class="dashboard-card-title">Mis Envíos</div>
                    <div class="dashboard-card-value">7</div> {{-- Dynamic data placeholder (e.g., {{ $student->submitted_posts_count }}) --}}
                    <div class="dashboard-card-description">Total de publicaciones enviadas.</div>
                </div>

                <div class="dashboard-card">
                    <i class="fas fa-check-circle dashboard-card-icon"></i> {{-- Icon for approved posts --}}
                    <div class="dashboard-card-title">Envíos Aprobados</div>
                    <div class="dashboard-card-value">5</div> {{-- Dynamic data placeholder (e.g., {{ $student->approved_posts_count }}) --}}
                    <div class="dashboard-card-description">Publicaciones aprobadas y visibles.</div>
                </div>

                <div class="dashboard-card">
                    <i class="fas fa-hourglass-half dashboard-card-icon"></i> {{-- Icon for pending posts --}}
                    <div class="dashboard-card-title">En Revisión</div>
                    <div class="dashboard-card-value">2</div> {{-- Dynamic data placeholder (e.g., {{ $student->pending_posts_count }}) --}}
                    <div class="dashboard-card-description">Publicaciones pendientes de revisión.</div>
                </div>

                <div class="dashboard-card">
                    <i class="fas fa-star dashboard-card-icon"></i> {{-- Icon for engagement/impact --}}
                    <div class="dashboard-card-title">Impacto de mis Posts</div>
                    <div class="dashboard-card-value">450</div> {{-- Dynamic data placeholder (e.g., {{ $student->total_post_interactions }}) --}}
                    <div class="dashboard-card-description">Total de interacciones en tus publicaciones.</div>
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
                <a href="" class="button_body"> {{-- Example route for editing profile --}}
                    <i class="fas fa-user-edit icon-left"></i> Editar Perfil
                </a>
                <a href="" class="button_body"> {{-- Example route for general settings --}}
                    <i class="fas fa-cog icon-left"></i> Ajustes de Cuenta
                </a>
            </div>
        </div>
    </main>

</x-layout>