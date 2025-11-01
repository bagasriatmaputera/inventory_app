<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['manager', 'keeper', 'customer'];
        $permissions = ['create role', 'edit role', 'delete role', 'view role'];

        // buat role
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
        }

        // buat permission
        foreach ($permissions as $permissionsRole) {
            $permission = Permission::firstOrCreate(['name' => $permissionsRole]);
        }

        // menetapkan permission pada role manager
        $managerRole = Role::where('name', 'manager')->first();
        $managerRole->givePermissionTo($permissions);

        // buat user
        foreach ($roles as $roleUser) {
            $user = User::factory()->create([
                'name' => ucfirst($roleUser) . 'User',
                'email' => $roleUser . '@example.com',
                'phone' => fake()->phoneNumber(),
                'photo' => fake()->imageUrl(200, 200, 'people', true, 'profile'),
                'password' => Hash::make('12345678'),
            ]);

            $user->assignRole($roleUser);
        }
    }
}
