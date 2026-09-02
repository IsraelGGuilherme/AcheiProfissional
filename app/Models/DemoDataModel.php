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
            'profissionais' => $this->profissionais(),
        ];
    }

    public function profissionalPorSlug(string $slug): ?array
    {
        foreach ($this->profissionais() as $profissional) {
            if ($profissional['slug'] === $slug) {
                return $profissional;
            }
        }

        return null;
    }

    private function profissionais(): array
    {
        return [
            [
                'slug' => 'joao-eletricista', 'nome' => 'João da Silva', 'profissao' => 'Eletricista',
                'cidade' => 'Juiz de Fora - MG', 'avaliacao' => '4,9', 'avaliacoes' => 37, 'inicial' => 'JS',
                'descricao' => 'Instalações elétricas residenciais, manutenção de quadros de energia e iluminação.',
                'servicos' => ['Instalação elétrica', 'Manutenção residencial', 'Iluminação LED'],
                'atendimento' => 'Atende em domicílio',
            ],
            [
                'slug' => 'mariana-designer', 'nome' => 'Mariana Oliveira', 'profissao' => 'Designer',
                'cidade' => 'Juiz de Fora - MG', 'avaliacao' => '4,8', 'avaliacoes' => 29, 'inicial' => 'MO',
                'descricao' => 'Identidade visual, materiais para redes sociais e criação de peças personalizadas.',
                'servicos' => ['Identidade visual', 'Artes para redes sociais', 'Materiais gráficos'],
                'atendimento' => 'Atendimento online e presencial',
            ],
            [
                'slug' => 'carlos-encanador', 'nome' => 'Carlos Mendes', 'profissao' => 'Encanador',
                'cidade' => 'Matias Barbosa - MG', 'avaliacao' => '4,7', 'avaliacoes' => 31, 'inicial' => 'CM',
                'descricao' => 'Serviços hidráulicos, instalação de torneiras e solução de vazamentos.',
                'servicos' => ['Caça-vazamentos', 'Instalações hidráulicas', 'Manutenção'],
                'atendimento' => 'Atende em domicílio',
            ],
            [
                'slug' => 'tiago-informatica', 'nome' => 'Tiago Informática', 'profissao' => 'Técnico em informática',
                'cidade' => 'Muriaé - MG', 'avaliacao' => '4,6', 'avaliacoes' => 22, 'inicial' => 'TI',
                'descricao' => 'Formatação, manutenção de notebooks, montagem de redes e instalação de câmeras.',
                'servicos' => ['Formatação', 'Manutenção de notebooks', 'Redes e câmeras'],
                'atendimento' => 'Atende em domicílio',
            ],
            [
                'slug' => 'maria-instalacoes', 'nome' => 'Maria Instalações', 'profissao' => 'Eletricista',
                'cidade' => 'Muriaé - MG', 'avaliacao' => '4,5', 'avaliacoes' => 18, 'inicial' => 'MI',
                'descricao' => 'Especialista em instalação residencial, quadros de energia, ventiladores e iluminação LED.',
                'servicos' => ['Instalação residencial', 'Quadros de energia', 'Iluminação LED'],
                'atendimento' => 'Atende em domicílio',
            ],
        ];
    }

    public function perfilContratante(): array
    {
        return ['usuario' => ['nome' => 'Ana Paula Souza', 'email' => 'ana.souza@email.com', 'telefone' => '(32) 99999-0000', 'cidade' => 'Juiz de Fora', 'estado' => 'MG']];
    }

    public function perfilProfissional(): array
    {
        return ['usuario' => ['nome' => 'João da Silva', 'email' => 'joao@email.com', 'telefone' => '(32) 98888-0001', 'profissao' => 'Eletricista', 'cidade' => 'Juiz de Fora', 'estado' => 'MG']];
    }

    public function administracao(): array
    {
        return ['resumo' => ['usuarios' => 128, 'profissionais' => 76, 'categorias' => 18, 'avaliacoes' => 342]];
    }
}
