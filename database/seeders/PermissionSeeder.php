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
            'Review Artikel',
            'Update Pengaturan Website',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
