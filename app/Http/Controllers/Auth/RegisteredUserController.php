<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Estudiante;
use App\Models\Profesor;
use App\Models\Administrador;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cedula' => ['string','max:8','required'],
        ]);
        if ($request->hasFile('foto_perfil')) {
            $image = $request->file('foto_perfil');
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $filename = $originalName . "_" . $request->cedula . '.' . $extension;
            $path = $image->storeAs('foto_perfil', $filename, 'public');
            $foto_perfil = 'foto_perfil/' . $filename;
        } else {
            $foto_perfil = 'foto_perfil/default.png'; 
        }

        if(Estudiante::where('cedula', $request->cedula)->exists()){
            $rol = "estudiante";
        
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'cedula' => $request->cedula,
                'rol' => $rol,
                'foto_perfil' => $foto_perfil
            ]);
        }

        elseif(Profesor::where('cedula', $request->cedula)->exists()){
            $rol = "profesor";
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'cedula' => $request->cedula,
                'rol' => $rol,
                'foto_perfil' => $foto_perfil
            ]);
        }
        elseif(Administrador::where('cedula', $request->cedula)->exists()){
            $rol = "administrador";
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'cedula' => $request->cedula,
                'rol' => $rol,
                'foto_perfil' => $foto_perfil
            ]);
        }

        else{
            return redirect()->back()->withErrors(['cedula' => 'La cédula no está registrada en el sistema.']);
        }

        event(new Registered($user));

        Auth::login($user);
        if ($rol === 'estudiante') {
            return redirect(route('estudiante.index', absolute: false));
        } elseif ($rol === 'profesor') {
            return redirect(route('profesor.index', absolute: false));
        } elseif ($rol === 'administrador') {
            return redirect(route('administrador.index', absolute: false));
        }
        else {
            // Si el rol no es reconocido, redirigir a una página de error o inicio
            return redirect(route('inicio', absolute: false))->withErrors(['rol' => 'Rol no reconocido.']);
        }
    }
}
