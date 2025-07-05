<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            'rol' => ['string','required'],
        ]);
        if(empty($foto_perfil) ){
            $foto_perfil = 'foto_perfil/default.png'; // Default profile picture
        }
        else{
            $foto_perfil;
        }
        $foto_perfil = 'foto_perfil/default.png'; // Default profile picture
        if ($request->hasFile('foto_perfil')) {
            $image = $request->file('foto_perfil');
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $filename = $originalName . "_" . $request->cedula . '.' . $extension;
            $path = $image->storeAs('foto_perfil', $filename, 'public');
            $foto_perfil = 'foto_perfil/' . $filename;
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cedula' => $request->cedula,
            'rol' => $request->rol,
            'foto_perfil' => $foto_perfil
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('estudiante.index', absolute: false));
    }
}
