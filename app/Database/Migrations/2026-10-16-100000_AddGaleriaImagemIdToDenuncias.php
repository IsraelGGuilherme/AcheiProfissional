<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Permite denunciar uma foto específica da galeria de trabalhos.
 * (Denúncia de avaliação já era possível pela coluna avaliacao_id.)
 * Se a foto for removida, a denúncia continua registrada, só sem o vínculo (SET NULL).
 */
class AddGaleriaImagemIdToDenuncias extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('galeria_imagem_id', 'denuncias')) {
            return;
        }

        $this->forge->addColumn('denuncias', [
            'galeria_imagem_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'avaliacao_id',
            ],
        ]);

        $this->db->query(
            'ALTER TABLE denuncias ADD CONSTRAINT denuncias_galeria_imagem_id_foreign '
            . 'FOREIGN KEY (galeria_imagem_id) REFERENCES profissional_imagens_galeria (id) '
            . 'ON DELETE SET NULL ON UPDATE CASCADE'
        );
    }

    public function down()
    {
        if (!$this->db->fieldExists('galeria_imagem_id', 'denuncias')) {
            return;
        }

        $this->db->query('ALTER TABLE denuncias DROP FOREIGN KEY denuncias_galeria_imagem_id_foreign');
        $this->forge->dropColumn('denuncias', 'galeria_imagem_id');
    }
}
