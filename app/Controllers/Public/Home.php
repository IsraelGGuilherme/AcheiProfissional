<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Services\BuscaProfissionaisService;

class Home extends BaseController
{
    public function index()
    {
        helper('profissional');

        $busca = new BuscaProfissionaisService();

        $filtros = [
            'categoria' => (int) $this->request->getGet('categoria'),
            'cidade'    => (int) $this->request->getGet('cidade'),
            'bairro'    => (int) $this->request->getGet('bairro'),
            'nota'      => (int) $this->request->getGet('nota'),
        ];

        // Usuário logado que ainda não escolheu cidade: a busca começa na cidade do cadastro dele.
        // Se a URL já traz o parâmetro "cidade" (mesmo vazio = "Todas"), a escolha dele é respeitada.
        $cidadePadrao = null;
        $usuario      = session('usuario');

        if ($usuario && $this->request->getGet('cidade') === null && $filtros['bairro'] === 0) {
            $cidadePadrao = $busca->cidadeDoUsuario((int) $usuario->id);

            if ($cidadePadrao !== null) {
                $filtros['cidade'] = (int) $cidadePadrao->id;
            }
        }

        $cidades = $busca->cidadesComProfissionais();

        // A cidade filtrada precisa existir no select, mesmo que ninguém ainda atenda lá.
        if ($filtros['cidade'] > 0 && !in_array($filtros['cidade'], array_map(static fn ($c) => (int) $c->id, $cidades), true)) {
            $extra = $busca->cidadePorId($filtros['cidade']);

            if ($extra !== null) {
                $cidades[] = $extra;
                usort($cidades, static fn ($a, $b) => strcmp($a->nome, $b->nome));
            }
        }

        $pagina    = (int) $this->request->getGet('pagina');
        $resultado = $busca->buscar($filtros, $pagina);

        return view('Public/Home/index', [
            'filtros'      => $filtros,
            'cidadePadrao' => $cidadePadrao,
            'categorias' => $busca->categorias(),
            'cidades'    => $cidades,
            'bairros'    => $filtros['cidade'] > 0 ? $busca->bairrosDaCidade($filtros['cidade']) : [],
            'resultado'  => $resultado,
        ]);
    }
}
