<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Fornece dados estáticos apenas para a demonstração visual do protótipo.
 * A persistência poderá ser adicionada posteriormente sem alterar as views.
 */
class DemoDataModel extends Model
{
    public function busca(): array
    {
        return [
            'categorias' => ['Eletricista', 'Encanador', 'Pintor', 'Fotógrafo', 'Designer'],
            'profissionais' => [
                [
                    'nome' => 'João da Silva',
                    'profissao' => 'Eletricista',
                    'cidade' => 'Juiz de Fora - MG',
                    'avaliacao' => '4,9',
                    'inicial' => 'JS',
                ],
                [
                    'nome' => 'Mariana Oliveira',
                    'profissao' => 'Designer',
                    'cidade' => 'Juiz de Fora - MG',
                    'avaliacao' => '4,8',
                    'inicial' => 'MO',
                ],
                [
                    'nome' => 'Carlos Mendes',
                    'profissao' => 'Encanador',
                    'cidade' => 'Matias Barbosa - MG',
                    'avaliacao' => '4,7',
                    'inicial' => 'CM',
                ],
            ],
        ];
    }

    public function perfilContratante(): array
    {
        return [
            'usuario' => [
                'nome' => 'Ana Paula Souza',
                'email' => 'ana.souza@email.com',
                'telefone' => '(32) 99999-0000',
                'cidade' => 'Juiz de Fora',
                'estado' => 'MG',
            ],
        ];
    }

    public function perfilProfissional(): array
    {
        return [
            'usuario' => [
                'nome' => 'João da Silva',
                'email' => 'joao@email.com',
                'telefone' => '(32) 98888-0001',
                'profissao' => 'Eletricista',
                'cidade' => 'Juiz de Fora',
                'estado' => 'MG',
            ],
        ];
    }

    public function administracao(): array
    {
        return [
            'resumo' => [
                'usuarios' => 128,
                'profissionais' => 76,
                'categorias' => 18,
                'avaliacoes' => 342,
            ],
        ];
    }
}
