<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role   =   Role::create(['name' => 'Super Administrator']);

        $permissions    =   [
            'List Role',
            'Tambah Role',
            'Edit Role',
            'List User',
            'Tambah User',
            'Edit User',
            'Reset Password User',
            'List Kategori',
            'Tambah Kategori',
            'Edit Kategori',
            'List Artikel',
            'Tambah Artikel',
            'Edit Artikel',
            'Approve Artikel',
            'Update Pengaturan Website',
        ];

        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }
    }
}
