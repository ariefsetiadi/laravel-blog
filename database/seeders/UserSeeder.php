<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Carbon\Carbon;
use Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user   =   [
            [
                'name'          =>  'User Administrator',
                'email'         =>  'admin@email.com',
                'password'      =>  Hash::make('Pass12345'),
                'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ];

        foreach ($user as $user) {
            $arr    =   User::firstOrCreate($user);
        }
    }
}
