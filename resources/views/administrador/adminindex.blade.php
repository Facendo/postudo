<x-layout title='PostUDO || Inicio'>
    
        <section class="main-content-section">
            <div class="profile-section">
                <img src="a" alt="Avatar del Administrador" class="profile-avatar">
                <h2 class="profile-name">¡Bienvenido, Administrador!</h2>
                <p class="profile-role">Gestione todas las funciones del sistema desde aquí.</p>
            </div>

            <div class="main-content-section">
        <div class="action-buttons-container">
            <a href="#" class="button_body">
                <i class="fa-solid fa-square-poll-vertical icon-left"></i> Editar Perfil
            </a>
            <a href="{{ route('estudiante.list')}}" class="button_body">
                <i class="fa-solid fa-users icon-left"></i> Gestion de Estudiantes
            </a>
            <a href="#" class="button_body">
                <i class="fa-solid fa-credit-card icon-left"></i> Gestion de Profesores
            </a>
            <a href="#" class="button_body">
                <i class="fa-solid fa-calendar-alt icon-left"></i> Gestor de Reportes
            </a>
            <a href="#" class="button_body">
                <i class="fa-solid fa-book icon-left"></i> Creacion pancho esta loco
            </a>
            <a href="{{ route('administrador.creacion.index') }}" class="button_body">
                <i class="fa-solid fa-book icon-left"></i> Creacion de A,C,E
            </a>
            

        </div>
    </div>



</x-layout>