<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlatformsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order = 1;
        $datas = [
            [
                'name' => 'La premiére brique',
                'category_id' => 1,
                'description' => 'La premiére brique',
                'link' => 'https://www.lapremierebrique.fr/fr/users/sign_up/TODNRE',
                'image_path' => 'img/platforms/la-premiere-brique.png',
                'order' => $order++,
            ],
        ];
        foreach ($datas as $key => $data) {
            DB::table('platforms')->insert([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'image_path' => Str::slug($data['image_path']),
                'category_id' => $data['category_id'],
                'order' => $data['order'],
                'description' => $data['description'],
                'link' => $data['link'],
            ]);
        }
    }
}
