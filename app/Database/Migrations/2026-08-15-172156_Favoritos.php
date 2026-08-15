<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Favoritos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'usuario_comum_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => false
            ],
            'profissional_id' => [
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
            'usuario_comum_id', 'usuarios_comuns', 'id', 'CASCADE', 'CASCADE'
        );
        $this->forge->addForeignKey(
            'profissional_id', 'profissionais', 'id', 'CASCADE', 'CASCADE'
        );
        $this->forge->addKey(
            ['usuario_comum_id', 'profissional_id'], false, true,
            'uk_favoritos_usuario_profissional'
        );
        $this->forge->createTable('favoritos');
    }

    public function down()
    {
        // $this->forge->dropTable('favoritos');
    }
}
