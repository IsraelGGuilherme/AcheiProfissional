<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ReconcileEmailVerificationColumn extends Migration
{
    public function up()
    {
        /** @var \CodeIgniter\Database\BaseConnection $database */
        $database = $this->db;

        if (!$database->fieldExists('email_verificado_em', 'usuarios')) {
            $this->forge->addColumn('usuarios', [
                'email_verificado_em' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
        }

        $this->db->query(
            "UPDATE usuarios
             SET email_verificado_em = COALESCE(created_at, CURRENT_TIMESTAMP)
             WHERE status = 'ATIVO' AND email_verificado_em IS NULL"
        );
    }

    public function down()
    {
        // Keep the column: existing installations may have had it before this migration.
    }
}
