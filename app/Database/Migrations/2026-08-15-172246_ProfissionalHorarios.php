<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class ProfissionalHorarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'      => 'BIGINT',
                'unsigned'  => true,
            ],
            'profissional_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => false
            ],
            'dia_semana' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'DOMINGO', 'SEGUNDA', 'TERCA','QUARTA',
                    'QUINTA', 'SEXTA', 'SABADO'
                ],
                'null'       => false,
            ],
            'abre' => [
                'type'       => 'TIME',
                'null'       => false,
            ],
            'fecha' => [
                'type'       => 'TIME',
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
        $this->forge->createTable('profissional_horarios');
    }

    public function down()
    {
        // $this->forge->dropTable('profissional_horarios');
    }
}
