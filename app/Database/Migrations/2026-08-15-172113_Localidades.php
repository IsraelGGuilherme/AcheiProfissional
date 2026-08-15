<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Localidades extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tipo' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'PAIS', 'UF', 'MUNICIPIO', 'BAIRRO'
                ],
                'null'       => false,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'sigla' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
                'null'       => true,
            ],
            'codigo_externo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'fonte_localidade_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'localidade_pai_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
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
            'fonte_localidade_id', 'fontes_localidades', 'id', 'CASCADE', 'SET NULL'
        );
        $this->forge->addForeignKey(
            'localidade_pai_id', 'localidades', 'id', 'CASCADE', 'RESTRICT'
        );
        $this->forge->createTable('localidades');
    }

    public function down()
    {
        // $this->forge->dropTable('localidades');
    }
}
