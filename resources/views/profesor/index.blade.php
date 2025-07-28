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
                <h2 class="profile-name">Dr. [Nombre del Profesor]</h2>
                
                
            </div>


            <h2 class="titulo" style="font-size: 26px; margin-bottom: 25px;">Acciones</h2>

            <div class="action-buttons-container">
                <a href="#" class="button_body">
                    <i class="fas fa-plus-circle icon-left"></i> Nueva Publicación
                </a>
                <a href="#" class="button_body" style="background-color: #007bff;">
                    <i class="fas fa-list-alt icon-left"></i> Ver Mis Publicaciones
                </a>
                <a href="#" class="button_body" style="background-color: #ffc107; color: #ffffff;">
                    <i class="fas fa-user-circle icon-left"></i> Editar Perfil
                </a>
                 <a href="#" class="button_body" style="background-color: #6c757d;">
                    <i class="fas fa-chart-bar icon-left"></i> Reportes
                </a>
            </div>
        </div>
    </main>



</x-layout>