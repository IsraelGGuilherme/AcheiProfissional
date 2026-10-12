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

        $pagina    = (int) $this->request->getGet('pagina');
        $resultado = $busca->buscar($filtros, $pagina);

        return view('Public/Home/index', [
            'filtros'    => $filtros,
            'categorias' => $busca->categorias(),
            'cidades'    => $busca->cidadesComProfissionais(),
            'bairros'    => $filtros['cidade'] > 0 ? $busca->bairrosDaCidade($filtros['cidade']) : [],
            'resultado'  => $resultado,
        ]);
    }
}
