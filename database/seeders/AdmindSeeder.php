<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administrador;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdmindSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Administrador::create([
            'cedula' => '12345678',
            'nombre' => 'Administrador',
            'correo' => 'admin@admin.com',
            'telefono' => '1234567890',
        ]);
         User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'), 
            'rol' => 'administrador', 
            'cedula' => '12345678', 
            'foto_perfil' => 'foto_perfil/default.png',
        ]);

        User::create([
            'name' => 'Francisco',
            'email' => 'francisco@estudiante.com',
            'password' => Hash::make('12345678'), 
            'rol' => 'estudiante', 
            'cedula' => '28649925', 
            'foto_perfil' => 'foto_perfil/default.png',
        ]);
    }
}
