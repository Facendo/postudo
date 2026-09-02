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
                <span>Gestión de Asuntos</span>
            </a>
            <a href="{{ route('pago.controlpagos') }}" class="admin-card">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Control de Pagos</span>
            </a>
        </div>
    </div>
</x-layout>