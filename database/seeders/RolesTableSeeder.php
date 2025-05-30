<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'المحل 1'],
            ['name' => 'موزع'],
            ['name' => 'خدمه العملاء'],
            ['name' => 'مدير مبيعات'],
            ['name' => 'MARKET'],
            ['name' => 'متابعه'],
            ['name' => 'tele sales'],
            ['name' => 'مدير عام'],
            ['name' => 'اكتيفيشن'],
        ]);

    }
}
