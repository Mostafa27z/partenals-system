<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            PermissionsTableSeeder::class,
            CustomerSeeder::class,
        ]);

        // إنشاء Admin فقط إذا لم يكن موجوداً
        User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role_id' => 1, // تأكد أن هذا الدور موجود
            ]
        );
    }
}
