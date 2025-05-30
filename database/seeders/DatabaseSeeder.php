<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
$this->call([
        RolesTableSeeder::class,
        
        PermissionsTableSeeder::class,
    ]);
        
        User::create([
    'name' => 'Admin',
    'email' => 'admin@demo.com',
    'password' => Hash::make('password'),
    'role_id' => 1, // لو فيه عمود role_id
]);
    }
}
