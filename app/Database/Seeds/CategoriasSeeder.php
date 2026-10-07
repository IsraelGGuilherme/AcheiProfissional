<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CategoriasSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            'Pedreiro',
            'Eletricista',
            'Encanador',
            'Pintor',
            'Carpinteiro / Marceneiro',
            'Diarista / Faxineira',
            'Jardineiro',
            'Montador de Móveis',
            'Chaveiro',
            'Técnico em Refrigeração',
            'Marido de Aluguel',
            'Gesseiro',
            'Serralheiro',
            'Vidraceiro',
            'Limpeza de Estofados',
            'Cuidador de Idosos',
            'Babá',
            'Piscineiro',
            'Enfermeiro(a) Particular',
            'Esteticista em Domicílio',
            'Manicure e Pedicure',
            'Cabelereiro(a) em Domicílio',
            'Adestrador de Cães',
            'Passeador de Cães (Dog Walker)'
        ];

        $data = [];
        helper('text');

        foreach ($categorias as $cat) {
            $data[] = [
                'nome'       => $cat,
                'slug'       => url_title($cat, '-', true),
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ];
        }

        // Using ignore to avoid duplicate entry errors if run multiple times
        $this->db->table('categorias')->ignore(true)->insertBatch($data);
    }
}
