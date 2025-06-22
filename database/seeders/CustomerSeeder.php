<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Line;
use App\Models\User;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تأكد من وجود مستخدم واحد على الأقل لإسناده في خطوط العملاء
        $user = User::firstOrCreate(
            ['email' => 'demo@user.com'],
            [
                'name' => 'Demo User',
                'password' => bcrypt('password'),
                'role_id' => 2, // مثلاً: موزع
            ]
        );

        // إنشاء 20 عميل
        Customer::factory()
            ->count(20)
            ->create()
            ->each(function ($customer) use ($user) {
                // لكل عميل، أنشئ 1-3 خطوط
                Line::factory()->count(rand(1, 3))->create([
                    'customer_id' => $customer->id,
                    'added_by'    => $user->id,
                ]);
            });
    }
}
