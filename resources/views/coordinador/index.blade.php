<x-layout title='PostUDO || Coordinador General - Inicio'>

    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <div class="admin-dashboard-container">
        <div class="admin-header">
            <h2 class="admin-title">¡Bienvenido, Coordinador!</h2>
            <p class="admin-subtitle">Panel de Coordinación General del sistema.</p>
        </div>

        <div class="admin-grid">
            <a href="{{ route('administrador.estudiantes_por_postgrado') }}" class="admin-card">
                <i class="fa-solid fa-users"></i>
                <span>Estudiantes</span>
            </a>
            <a href="{{ route('registro_estudiante.index') }}" class="admin-card">
                <i class="fa-solid fa-user-plus"></i>
                <span>Registrar Estudiante</span>
            </a>
            <a href="{{ route('administrador.creacion.index') }}" class="admin-card">
                <i class="fa-solid fa-layer-group"></i>
                <span>Áreas</span>
            </a>
            <a href="#" class="admin-card">
                <i class="fa-solid fa-chalkboard-teacher"></i>
                <span>Profesores</span>
            </a>
            <a href="{{ route('administrador.gestion_postgrado') }}" class="admin-card">
                <i class="fa-solid fa-book-open"></i>
                <span>Postgrados</span>
            </a>
        </div>
    </div>

</x-layout>