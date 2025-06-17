<x-layout title="PostUDO || Estudiante" ruta="estudiante.index">
    <div class="content_texto_bienvenida">
        <label>Bienvenido {{ $user->name }}</label>
    </div>

    <div class="main-content-section">
        <div class="action-buttons-container">
            <a href="#" class="button_body">
                <i class="fa-solid fa-square-poll-vertical icon-left"></i> Historico Academico
            </a>
            <a href="#" class="button_body">
                <i class="fa-solid fa-users icon-left"></i> Cohortes inscritos
            </a>
            <a href="#" class="button_body">
                <i class="fa-solid fa-credit-card icon-left"></i> Historico de Pagos
            </a>
            <a href="#" class="button_body">
                <i class="fa-solid fa-calendar-alt icon-left"></i>Horario Academico
            </a>
            <a href="#" class="button_body">
                <i class="fa-solid fa-book icon-left"></i> Pensum
            </a>
            

        </div>
    </div>
</x-layout>