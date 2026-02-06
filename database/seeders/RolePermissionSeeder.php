<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\VotoUsuario;
use App\Models\VotoPersona;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'sanctum';

        $permissions = [
            'ver-dashboard',
            'ver-personas', 'crear-personas', 'editar-personas', 'eliminar-personas',
            'ver-usuarios', 'crear-usuarios', 'editar-usuarios', 'eliminar-usuarios', 'asignar-roles',
            'ver-geografico', 'crear-geografico', 'editar-geografico', 'eliminar-geografico',
            'ver-mesas', 'crear-mesas', 'editar-mesas', 'eliminar-mesas',
            'ver-tipos-eleccion', 'crear-tipos-eleccion', 'editar-tipos-eleccion', 'eliminar-tipos-eleccion',
            'ver-partidos', 'crear-partidos', 'editar-partidos', 'eliminar-partidos',
            'ver-candidatos', 'crear-candidatos', 'editar-candidatos', 'eliminar-candidatos',
            'registrar-votos', 'ver-resultados', 'exportar-resultados',
            'ver-logs', 'ver-reportes', 'configurar-sistema'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guard,
            ]);
        }

        $roleAdmin = Role::firstOrCreate(['name' => 'ADMIN', 'guard_name' => $guard]);
        $roleOperador = Role::firstOrCreate(['name' => 'OPERADOR', 'guard_name' => $guard]);
        $roleAdminSec = Role::firstOrCreate(['name' => 'ADMIN_SECUNDARIO', 'guard_name' => $guard]);

        $roleAdmin->syncPermissions(Permission::where('guard_name', $guard)->get());

        $roleOperador->syncPermissions([
            'ver-dashboard',
            'ver-personas',
            'registrar-votos',
            'ver-resultados'
        ]);

        $roleAdminSec->syncPermissions([
            'ver-dashboard',
            'ver-personas', 'crear-personas', 'editar-personas',
            'ver-usuarios', 'crear-usuarios', 'editar-usuarios',
            'ver-geografico', 'crear-geografico', 'editar-geografico',
            'ver-mesas', 'crear-mesas', 'editar-mesas',
            'ver-tipos-eleccion', 'crear-tipos-eleccion', 'editar-tipos-eleccion',
            'ver-partidos', 'crear-partidos', 'editar-partidos',
            'ver-candidatos', 'crear-candidatos', 'editar-candidatos'
        ]);

        $personaAdmin = VotoPersona::firstOrCreate(
            ['ci' => '0000000'],
            [
                'nombre' => 'Administrador',
                'apellido_paterno' => 'Sistema',
                'apellido_materno' => 'Balotaje',
                'email' => 'admin@balotaje.com'
            ]
        );

        $usuarioAdmin = VotoUsuario::firstOrCreate(
            ['nombre_usuario' => 'admin'],
            [
                'contrasena' => 'admin123',
                'id_persona' => $personaAdmin->id_persona,
                'activo' => true
            ]
        );

        if (!$usuarioAdmin->hasRole('ADMIN')) {
            $usuarioAdmin->assignRole($roleAdmin);
        }

        $this->command->info('✅ Roles y permisos creados exitosamente!');
        $this->command->info('   Usuario admin: admin / admin123');
        $this->command->info('   Roles: ADMIN, OPERADOR, ADMIN_SECUNDARIO');
    }
}
