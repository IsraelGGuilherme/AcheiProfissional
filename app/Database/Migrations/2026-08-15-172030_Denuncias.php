<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Denuncias extends Migration
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
            'usuario_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true
            ],
            'avaliacao_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true
            ],
            'motivo' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'CONTEUDO_INADEQUADO', 'INFORMACAO_FALSA',
                    'SPAN', 'FRAUDE', 'OFENSA', 'OUTRO'
                ],
                'null'       => false,
            ],
            'descricao' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'PENDENTE', 'EM_ANALISE',
                    'RESOLVIDA', 'REJEITADA'
                ],
                'null'       => false,
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
            'profissional_id', 'profissionais', 'id', 'CASCADE', 'SET NULL'
        );
        $this->forge->addForeignKey(
            'usuario_id', 'usuarios', 'id', 'CASCADE', 'SET NULL'
        );
        $this->forge->addForeignKey(
            'avaliacao_id', 'avaliacoes', 'id', 'CASCADE', 'CASCADE'
        );
        $this->forge->createTable('denuncias');
    }

    public function down()
    {
        // $this->forge->dropTable('denuncias');
    }
}
