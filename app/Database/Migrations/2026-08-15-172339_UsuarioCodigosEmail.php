<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class UsuarioCodigosEmail extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'usuario_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => false,
            ],
            'finalidade' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'VERIFICAR_EMAIL', 'RECUPERAR_SENHA'
                ],
                'null'       => false,
            ],
            'codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'tentativas' => [
                'type'       => 'TINYINT',
                'unsigned'   => true,
                'default'    => '0',
                'null'       => true,
            ],
            'utilizado' => [
                'type'       => 'BOOLEAN',
                'default'    => '0',
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
            'usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE'
        );
        $this->forge->createTable('usuario_codigos_email');
    }

    public function down()
    {
        // $this->forge->dropTable('usuario_codigos_email');
    }
}
