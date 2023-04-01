<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    $datas = [
        'Crowdfunding' => '',
        'Crowdlending' => '',
        'SCPI' => '',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order = 1;
        foreach ($datas as $name => $description) {
            DB::table('categories')->insert([
                'name' => $name,
                'order' => $order++,
                'description' => $description,
            ]);
        }

    }
}
