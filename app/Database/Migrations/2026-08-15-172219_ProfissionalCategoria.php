<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class ProfissionalCategoria extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'profissional_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => false
            ],
            'categoria_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => false
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey(
            'profissional_id', 'profissionais', 'id', 'CASCADE', 'CASCADE'
        );
        $this->forge->addForeignKey(
            'categoria_id', 'categorias', 'id', 'CASCADE', 'CASCADE'
        );
        $this->forge->createTable('profissional_categoria');
    }

    public function down()
    {
        // $this->forge->dropTable('profissional_categoria');
    }
}
