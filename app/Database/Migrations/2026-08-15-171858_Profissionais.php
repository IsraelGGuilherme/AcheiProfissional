<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Profissionais extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'      => 'BIGINT',
                'unsigned'  => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'descricao' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'foto_capa' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'telefone_contato' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'whatsapp' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'instagram' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'site' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'cpf_cnpj' => [
                'type'       => 'VARCHAR',
                'constraint' => '14',
                'null'       => false,
            ],
            'tipo_pessoa' => [
                'type'       => 'ENUM',
                'constraint' => ['PF', 'PJ'],
                'null'       => false,
            ],
            'trabalha_feriados' => [
                'type'       => 'BOOLEAN',
                'null'       => true,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'unique'     => true,
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
        $this->forge->addForeignKey('id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('profissionais');
    }

    public function down()
    {
        // $this->forge->dropTable('profissionais');
    }
}
