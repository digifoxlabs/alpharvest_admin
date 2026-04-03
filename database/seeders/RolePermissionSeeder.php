<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'view stores',
            'create stores',
            'edit stores',
            'delete stores',
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
            'view chats',
            'create chats',
            'edit chats',
            'delete chats',
            'view templates',
            'create templates',
            'edit templates',
            'delete templates',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $managerRole = Role::firstOrCreate([
            'name' => 'manager',
            'guard_name' => 'web',
        ]);

        $adminRole->syncPermissions(Permission::where('guard_name', 'web')->pluck('name')->all());
        $managerRole->syncPermissions([
            'view dashboard',
            'view users',
            'view products',
            'view orders',
        ]);

        $adminUserData = [
            'name' => 'Admin',
            'password' => 'password@123',
        ];

        $managerUserData = [
            'name' => 'Manager',
            'password' => 'password@123',
        ];

        if (Schema::hasColumn('users', 'role_id')) {
            $adminUserData['role_id'] = $adminRole->id;
            $managerUserData['role_id'] = $managerRole->id;
        }

        $adminUser = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            $adminUserData
        );
        $adminUser->syncRoles(['admin']);

        $managerUser = User::updateOrCreate(
            ['email' => 'manager@gmail.com'],
            $managerUserData
        );
        $managerUser->syncRoles(['manager']);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
