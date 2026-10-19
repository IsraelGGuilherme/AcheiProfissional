<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriacaoTabelaBloqueioUsuario extends Migration
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
                'null'       => true
            ],
            'motivo' => [
                'type'       => 'VARCHAR',
                'constraint' =>  255,
                'null'       => false,
            ],
            'dt_desbloqueio' => [
                'type'    => 'TIMESTAMP',
                'null'    => true
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey(
            'profissional_id',
            'profissionais',
            'id',
            'CASCADE',
            'SET NULL'
        );
        $this->forge->createTable('bloqueio_usuario');
    }

    public function down()
    {
        $this->forge->dropTable('bloqueio_usuario');
    }
}
