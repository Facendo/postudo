<x-layout title='PostUDO || Estudiante'>

    <div class="admin-dashboard-container">
        <div class="admin-header">
            <h2 class="admin-title">¡Bienvenido, {{ $user->name }}!</h2>
        </div>

        <div class="admin-grid">
            <a href="{{ route('pago.index') }}" class="admin-card">
                <i class="fa-solid fa-credit-card"></i>
                <span>Histórico de Pagos</span>
            </a>
            <a href="{{ route('pago.create') }}" class="admin-card">
                <i class="fa-solid fa-file-alt"></i>
                <span>Registrar Pago</span>
            </a>
        </div>
    </div>
</x-layout>