<x-layout title='PostUDO || Coordinador de Postgrado - Inicio'>

    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <div class="admin-dashboard-container">
        <div class="admin-header">
            <h2 class="admin-title">¡Bienvenido, {{ Auth::user()->name }}!</h2>
            <p class="admin-subtitle">Panel de coordinación de tus postgrados asignados.</p>
        </div>

        {{-- Solo sus postgrados asignados: el filtrado lo hace el controller --}}
        @empty($postgradosAsignados)
            {{-- Aviso si perdió todos sus postgrados (p. ej. se eliminaron) --}}
            <p style="text-align: center; margin: 0 0 22px; padding: 12px 18px; border: 1px solid rgba(255, 193, 7, 0.55); background: rgba(255, 193, 7, 0.12); border-radius: 8px; color: #ffc107;">
                Actualmente no tienes postgrados asignados. Contacta con el coordinador general
                para que te asigne uno o actualice tu cuenta.
            </p>
        @endempty

        <div class="admin-grid">
            <a href="{{ route('administrador.gestion_postgrado') }}" class="admin-card">
                <i class="fa-solid fa-book-open"></i>
                <span>Mis Postgrados</span>
            </a>
            <a href="{{ route('administrador.gestioncohorte') }}" class="admin-card">
                <i class="fa-solid fa-layer-group"></i>
                <span>Cohortes</span>
            </a>
        </div>
    </div>

</x-layout>
