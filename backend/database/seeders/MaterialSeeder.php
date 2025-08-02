<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('materials')->insert([
            [
                'name' => 'Papel',
                'category' => 'seco',
                'description' => 'Resíduos de papel como jornais, papelão e livros',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Plástico',
                'category' => 'seco',
                'description' => 'Resíduos plásticos incluindo garrafas, sacolas e embalagens',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vidro',
                'category' => 'seco',
                'description' => 'Garrafas e recipientes de vidro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Metal',
                'category' => 'seco',
                'description' => 'Latas, folhas de alumínio e sucata metálica',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Eletrônicos',
                'category' => 'eletrônico',
                'description' => 'Resíduos eletrônicos como celulares antigos, baterias e computadores',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Orgânico',
                'category' => 'orgânico',
                'description' => 'Resíduos orgânicos como restos de comida e resíduos de jardim',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Têxtil',
                'category' => 'seco',
                'description' => 'Roupas usadas, tecidos e outros materiais têxteis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Madeira',
                'category' => 'seco',
                'description' => 'Resíduos de madeira e madeira não tratada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
