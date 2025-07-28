<x-layout title='PostUDO || Inicio'>
    
        <section class="main-content-section">
            <div class="profile-section">
                <img src="{{asset('foto_perfil/default.png')}}" alt="Avatar del Profesor" class="profile-avatar">
                <h2 class="profile-name">¡Bienvenido, Licenciado {{ Auth::user()->name }}!</h2>
            </div>

            <div class="main-content-section">
        <div class="action-buttons-container">
            <a href="{{ route('profesor.perfil') }}" class="button_body">
                <i class="fa-solid fa-square-poll-vertical icon-left"></i> Ver Perfil
            </a>
            <a href="#" class="button_body">
                <i class="fa-solid fa-users icon-left"></i> Gestion de Notas
            </a>
            <a href="{{ route('profesor.listadoestudiantes') }}" class="button_body">
                <i class="fa-solid fa-briefcase icon-left"></i> Listado de Estudiantes
            </a>

            <a href="{{ route('profesor.gestionevaluacion.index') }}" class="button_body">
                <i class="fa-solid fa-calendar-alt icon-left"></i> Gestion de Evaluaciones
            </a>
            <a href="#" class="button_body">
                <i class="fa-solid fa-building icon-left"></i> Consultar Horario Academico
            </a>

        </div>
    </div>

</x-layout>