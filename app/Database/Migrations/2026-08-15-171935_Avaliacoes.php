<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Avaliacoes extends Migration
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
            'usuario_comum_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true
            ],
            'nota' => [
                'type'       => 'TINYINT',
                'null'       => false,
            ],
            'comentario' => [
                'type'       => 'TEXT',
                'null'       => true,
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
            'usuario_comum_id', 'usuarios_comuns', 'id', 'CASCADE', 'SET NULL'
        );
        $this->forge->addKey(
            ['profissional_id', 'usuario_comum_id'], false, true,
            'uk_avaliacoes_profissional_usuario_comum'
        );
        $this->forge->createTable('avaliacoes');
    }

    public function down()
    {
        // $this->forge->dropTable('avaliacoes');
    }
}
