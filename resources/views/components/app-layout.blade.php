{{-- Ancla de componente: reutiliza la plantilla de panel que vive en layouts/app.blade.php --}}
@include('layouts.app', ['slot' => $slot, 'header' => $header ?? null])
