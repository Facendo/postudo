<x-layout title='PostUDO || Inicio'>
    <style>
        /* Agrega estilos específicos si los necesitas, aunque la mayoría deberían venir de tu CSS */
        
    </style>

    <main>
        <div class="main-content-section">
            <h1 class="titulo">Bienvenido, Profesor</h1>

            @if(session('status'))
                <div class="session-status-message">
                    {{ session('status') }}
                </div>
            @endif

            <div class="profile-section">
                {{-- Replace with actual professor's avatar --}}
                <img src="https://via.placeholder.com/120/024994/FFFFFF?text=Prof" alt="Avatar del Profesor" class="profile-avatar">
                <h2 class="profile-name">Dr. [Nombre del Profesor]</h2> {{-- Dynamic data placeholder --}}
                <p class="profile-role">Profesor de Informática</p> {{-- Dynamic data placeholder --}}
                <div class="content_texto_bienvenida">
                    <label>
                        "La educación es el arma más poderosa que puedes usar para cambiar el mundo."
                    </label>
                </div>
            </div>

            <hr style="width: 100%; border: 0; border-top: 1px solid #eee; margin: 30px 0;">

            <h2 class="titulo" style="font-size: 26px; margin-bottom: 25px;">Estadísticas Rápidas</h2>

            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <i class="fas fa-newspaper dashboard-card-icon"></i> {{-- Icon for posts --}}
                    <div class="dashboard-card-title">Mis Publicaciones</div>
                    <div class="dashboard-card-value">124</div> {{-- Dynamic data placeholder --}}
                    <div class="dashboard-card-description">Total de publicaciones creadas.</div>
                </div>

                <div class="dashboard-card">
                    <i class="fas fa-users dashboard-card-icon"></i> {{-- Icon for students/collaborators --}}
                    <div class="dashboard-card-title">Estudiantes Asignados</div>
                    <div class="dashboard-card-value">67</div> {{-- Dynamic data placeholder --}}
                    <div class="dashboard-card-description">Estudiantes bajo su tutela.</div>
                </div>

                <div class="dashboard-card">
                    <i class="fas fa-eye dashboard-card-icon"></i> {{-- Icon for views --}}
                    <div class="dashboard-card-title">Visualizaciones</div>
                    <div class="dashboard-card-value">5.3K</div> {{-- Dynamic data placeholder --}}
                    <div class="dashboard-card-description">Vistas totales de sus posts.</div>
                </div>

                <div class="dashboard-card">
                    <i class="fas fa-clipboard-check dashboard-card-icon"></i> {{-- Icon for approvals --}}
                    <div class="dashboard-card-title">Posts Aprobados</div>
                    <div class="dashboard-card-value">118</div> {{-- Dynamic data placeholder --}}
                    <div class="dashboard-card-description">Publicaciones aprobadas por la UDO.</div>
                </div>
            </div>

            <hr style="width: 100%; border: 0; border-top: 1px solid #eee; margin: 30px 0;">

            <h2 class="titulo" style="font-size: 26px; margin-bottom: 25px;">Acciones Rápidas</h2>

            <div class="action-buttons-container">
                <a href="#" class="button_body">
                    <i class="fas fa-plus-circle icon-left"></i> Nueva Publicación
                </a>
                <a href="#" class="button_body" style="background-color: #007bff;">
                    <i class="fas fa-list-alt icon-left"></i> Ver Mis Publicaciones
                </a>
                <a href="#" class="button_body" style="background-color: #ffc107; color: #333;">
                    <i class="fas fa-user-circle icon-left"></i> Editar Perfil
                </a>
                 <a href="#" class="button_body" style="background-color: #6c757d;">
                    <i class="fas fa-chart-bar icon-left"></i> Reportes
                </a>
            </div>
        </div>
    </main>



</x-layout>