<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VotoPersona;
use App\Models\VotoUsuario;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        echo " Creando usuario administrador...\n";
        
        // Persona para el admin
        $persona = VotoPersona::firstOrCreate(
            ['ci' => '0000000'],
            [
                'nombre' => 'Administrador',
                'apellido_paterno' => 'Sistema',
                'apellido_materno' => 'BD',
                'celular' => '77777777',
                'email' => 'admin@sistema.com',
            ]
        );
        
        // Usuario admin
        $usuario = VotoUsuario::updateOrCreate(
            ['nombre_usuario' => 'admin'],
            [
                'contrasena' => bcrypt('colca'), // CONTRASEÑA POR DEFECTO
                'id_persona' => $persona->id_persona,
                'activo' => true,
                'fecha_fin' => null,
                'token' => null,
            ]
        );
        
        // Asignar rolpublic function run()
{
    // 1. Crear persona
    $persona = \App\Models\VotoPersona::firstOrCreate(
        ['ci' => '0000000'],
        [
            'nombre' => 'Administrador',
            'apellido_paterno' => 'Sistema',
            'apellido_materno' => 'BD',
            'celular' => '77777777',
            'email' => 'admin@sistema.com',
        ]
    );
    
    // 2. Crear usuario
    $usuario = \App\Models\VotoUsuario::updateOrCreate(
        ['nombre_usuario' => 'admin'],
        [
            'contrasena' => bcrypt('colca'),
            'id_persona' => $persona->id_persona,
            'activo' => true,
            'fecha_fin' => null,
            'token' => null,
        ]
    );
    
    // 3. SOLUCIÓN: Buscar el rol especificando el guard
    $rolAdmin = \Spatie\Permission\Models\Role::where('name', 'ADMIN')
        ->where('guard_name', 'web') // o 'sanctum'
        ->first();
    
    if ($rolAdmin) {
        $usuario->assignRole($rolAdmin); // Asignar objeto Role en lugar de string
    } else {
        // Si no existe, crearlo con guard específico
        $rolAdmin = \Spatie\Permission\Models\Role::create([
            'name' => 'ADMIN',
            'guard_name' => 'web' // ← IMPORTANTE
        ]);
        $usuario->assignRole($rolAdmin);
    }
    
    echo " Usuario admin creado y rol asignado\n";
}
        $usuario->syncRoles(['ADMIN']);
        
        echo "USUARIO ADMIN CREADO:\n";
        echo "   Usuario: admin\n";
        echo "   Contraseña: colca\n";
        echo "   Persona ID: {$persona->id_persona}\n";
        echo "   CI: {$persona->ci}\n\n";
        
        echo " Para cambiar contraseña:\n";
        echo "   1. Login con admin/colca\n";
        echo "   2. Ir a configuración de usuario\n";
        echo "   3. Cambiar contraseña\n";
    }
}