<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'Crear FAQ']);
        Permission::create(['name' => 'Editar FAQ']);
        Permission::create(['name' => 'Eliminar FAQ']);

        Permission::create(['name' => 'Crear departamento']);
        Permission::create(['name' => 'Editar departamento']);
        Permission::create(['name' => 'Eliminar departamento']);

        Permission::create(['name' => 'Crear categoria']);
        Permission::create(['name' => 'Editar categoria']);
        Permission::create(['name' => 'Eliminar categoria']);

        Permission::create(['name' => 'Crear subcategoria']);
        Permission::create(['name' => 'Editar subcategoria']);
        Permission::create(['name' => 'Eliminar subcategoria']);
        

    }
}
