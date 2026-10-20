{{-- Ancla de componente: reutiliza la plantilla de invitado que vive en layouts/guest.blade.php --}}
@include('layouts.guest', ['slot' => $slot])
