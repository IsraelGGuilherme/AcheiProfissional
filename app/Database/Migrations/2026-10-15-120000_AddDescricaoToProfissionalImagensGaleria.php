<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Legenda (descrição curta) de cada foto da galeria de trabalhos do profissional.
 */
class AddDescricaoToProfissionalImagensGaleria extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('descricao', 'profissional_imagens_galeria')) {
            return;
        }

        $this->forge->addColumn('profissional_imagens_galeria', [
            'descricao' => [
                'type'       => 'VARCHAR',
                'constraint' => 300,
                'null'       => true,
                'after'      => 'arquivo',
            ],
        ]);
    }

    public function down()
    {
        if ($this->db->fieldExists('descricao', 'profissional_imagens_galeria')) {
            $this->forge->dropColumn('profissional_imagens_galeria', 'descricao');
        }
    }
}
