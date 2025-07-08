<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administrador;


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
    }
}
