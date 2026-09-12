<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;
use RuntimeException;

class LocalidadesSeeder extends Seeder
{
    public function run()
    {
        $filePath = APPPATH . 'Database/Seeds/data/localidades.sql';

        if (! file_exists($filePath)) {
            CLI::error("Arquivo não encontrado: {$filePath}");
            return;
        }

        // Validação preventiva contra duplicação de chave primária
        $totalAtual = $this->db->table('localidades')->countAllResults();
        if ($totalAtual > 0) {
            CLI::write("A tabela 'localidades' já contém {$totalAtual} registros.", 'yellow');
            
            if (is_cli()) {
                $confirmar = CLI::prompt(
                    'Deseja prosseguir mesmo assim? (Pode ocorrer erro de chave duplicada se as tabelas não estiverem vazias)',
                    ['s', 'n'],
                    'required'
                );

                if (strtolower($confirmar) !== 's') {
                    CLI::write("Operação cancelada pelo usuário.", 'light_gray');
                    return;
                }
            }
        }

        CLI::write("Iniciando importação de localidades via dump SQL...", 'cyan');
        $inicio = microtime(true);

        $sql = file_get_contents($filePath);
        $mysqli = $this->db->connID;

        // Executa todas as instruções do dump em lote de forma nativa
        if ($mysqli->multi_query($sql)) {
            do {
                if ($result = $mysqli->store_result()) {
                    $result->free();
                }
            } while ($mysqli->more_results() && $mysqli->next_result());
        }

        if ($mysqli->errno) {
            throw new RuntimeException("Erro ao executar dump SQL: " . $mysqli->error);
        }

        $tempo = number_format(microtime(true) - $inicio, 2);
        $totalLocalidades = $this->db->table('localidades')->countAllResults();
        $totalFontes = $this->db->table('fontes_localidades')->countAllResults();

        CLI::write("Povoamento concluído com sucesso em {$tempo}s!", 'green');
        CLI::write("- Total de fontes cadastradas: {$totalFontes}", 'green');
        CLI::write("- Total de localidades cadastradas: {$totalLocalidades}", 'green');
    }
}
