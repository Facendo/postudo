<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login'); 
        }
        $user = Auth::user(); 
        $requiredRoles = explode('|', $roles); 
        if (in_array($user->rol, $requiredRoles)) {
            return $next($request);
        }
        return redirect()->route('inicio')->with('error', 'No tienes los permisos necesarios para acceder a esta sección.');
        return $next($request);
    }
}
