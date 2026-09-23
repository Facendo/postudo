<x-layout title='PostUDO || Administrador - Inicio'>

    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <div class="admin-dashboard-container">
        <div class="admin-header">
            <h2 class="admin-title">¡Bienvenido!</h2>
            <p class="admin-subtitle">Gestione todas las funciones del sistema desde aquí.</p>
        </div>

        <div class="admin-grid">
            <a href="{{ route('asuntos.index') }}" class="admin-card">
                <i class="fa-solid fa-file-alt"></i>
                <span>Asuntos</span>
            </a>
            <a href="{{ route('pago.controlpagos') }}" class="admin-card">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Pagos</span>
            </a>
            <a href="{{ route('registro_estudiante.index') }}" class="admin-card">
                <i class="fa-solid fa-user-plus"></i>
                <span>Registrar Estudiante</span>
            </a>
            <a href="{{ route('pago.actualizados') }}" class="admin-card">
                <i class="fa-solid fa-circle-check"></i>
                <span>Pagos Actualizados</span>
            </a>
            <a href="{{ route('pago.pendientes') }}" class="admin-card">
                <i class="fa-solid fa-clock"></i>
                <span>Pagos Pendientes</span>
            </a>
            <a href="{{ route('administrador.especialidades.index') }}" class="admin-card">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Especialidades</span>
            </a>
            <a href="{{ route('administrador.prueba_especialidades') }}" class="admin-card">
                <i class="fa-solid fa-users-viewfinder"></i>
                <span>Estudiantes</span>
            </a>
        </div>
    </div>
</x-layout>