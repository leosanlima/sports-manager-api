<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se o papel de administrador já existe
        if (Role::where('name', 'admin')->exists()) {
            $this->command->info('Roles and permissions already exist. Skipping seed.');
            return; // Se já existir, parar o seeder aqui
        }

        // Resetar cache de papéis e permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar permissões
        Permission::firstOrCreate(['name' => 'create records']);
        Permission::firstOrCreate(['name' => 'edit records']);
        Permission::firstOrCreate(['name' => 'delete records']);
        Permission::firstOrCreate(['name' => 'view records']);

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(['create records', 'edit records', 'delete records', 'view records']);

        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->givePermissionTo(['create records', 'edit records', 'view records']); // Usuário não pode deletar


        // Criar o usuário admin se não existir
        if (!User::where('email', 'admin@admin.com')->exists()) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin.1234'), // Altere a senha conforme necessário
            ]);
            $admin->assignRole('admin');
        }

        // Criar o usuário regular se não existir
        if (!User::where('email', 'user@user.com')->exists()) {
            $user = User::create([
                'name' => 'Regular User',
                'email' => 'user@user.com',
                'password' => bcrypt('user.1234'), // Altere a senha conforme necessário
            ]);
            $user->assignRole('user');
        }
    }
}
