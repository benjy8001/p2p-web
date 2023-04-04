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
                'short_description' => 'La Première Brique vous permet de participer au financement de projets immobiliers à partir de 1€, et d’être rémunéré par des intérêts.',
                'link' => 'https://www.lapremierebrique.fr/fr/users/sign_up/TODNRE',
                'image_path' => 'img/platforms/la-premiere-brique.png',
                'order' => $order++,
            ],
            [
                'name' => 'Upstone',
                'category_id' => 1,
                'description' => 'Upstone',
                'short_description' => 'Upstone est une plateforme de financement dédiée aux projets immobiliers, accessible dés 100€.',
                'link' => 'https://www.upstone.co/',
                'image_path' => 'img/platforms/upstone.jpg',
                'order' => $order++,
            ],
            [
                'name' => 'Robocash',
                'category_id' => 2,
                'description' => 'Robocash',
                'short_description' => 'Robo.cash est une plateforme de crowdlending (crédits consos) entièrement automatisée qui vous permet de gagner jusqu’à 12 % par an avec un investissement à partir de 1€.',
                'link' => 'https://robo.cash/ref/agfy',
                'image_path' => 'img/platforms/robocash.png',
                'order' => $order=1,
            ],
        ];
        foreach ($datas as $key => $data) {
            DB::table('platforms')->insert([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'image_path' => $data['image_path'],
                'category_id' => $data['category_id'],
                'order' => $data['order'],
                'description' => $data['description'],
                'short_description' => $data['short_description'],
                'link' => $data['link'],
            ]);
        }
    }
}
