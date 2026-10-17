<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Models\AvaliacoesModel;
use App\Models\DenunciasModel;
use App\Models\GaleriaModel;
use App\Services\AvaliacaoService;
use App\Services\BuscaProfissionaisService;
use App\Services\ImagemUploadService;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Página pública de um profissional + ações de avaliar e denunciar.
 */
class Profissionais extends BaseController
{
    /** Motivos exibidos no formulário => valor do enum em denuncias.motivo */
    private const MOTIVOS_DENUNCIA = [
        'INFORMACAO_FALSA' => 'Informações falsas ou enganosas',
        'OFENSA'           => 'Comportamento inadequado',
        'FRAUDE'           => 'Contato ou anúncio suspeito',
        'OUTRO'            => 'Outro motivo',
    ];

    protected BuscaProfissionaisService $busca;

    public function __construct()
    {
        $this->busca = new BuscaProfissionaisService();
    }

    public function ver(string $slug)
    {
        helper('profissional');

        $profissional = $this->busca->perfilPorSlug($slug);

        if ($profissional === null) {
            throw PageNotFoundException::forPageNotFound('Profissional não encontrado.');
        }

        $db = db_connect();

        $avaliacoes = $db->table('avaliacoes a')
            ->select('a.id, a.usuario_comum_id, a.nota, a.comentario, a.created_at, uc.nome AS autor_nome')
            ->join('usuarios_comuns uc', 'uc.id = a.usuario_comum_id', 'left')
            ->where('a.profissional_id', $profissional->id)
            ->orderBy('a.created_at', 'DESC')
            ->orderBy('a.id', 'DESC')
            ->limit(20)
            ->get()
            ->getResult();

        $usuario     = session('usuario');
        $ehComum     = $usuario && $usuario->tipo === 'COMUM';
        $minhaNota   = null;

        if ($ehComum) {
            $minhaNota = model(AvaliacoesModel::class)
                ->where('profissional_id', $profissional->id)
                ->where('usuario_comum_id', $usuario->id)
                ->first();
        }

        $servicoAvaliacao = new AvaliacaoService();

        $fotosAvaliacoes = $servicoAvaliacao->fotosPorAvaliacoes(array_map(static fn ($a) => (int) $a->id, $avaliacoes));
        $minhasFotos     = $minhaNota ? $servicoAvaliacao->fotosDaAvaliacao((int) $minhaNota->id) : [];

        return view('Public/Profissionais/ver', [
            'profissional'    => $profissional,
            'avaliacoes'      => $avaliacoes,
            'fotosAvaliacoes' => $fotosAvaliacoes,
            'minhasFotos'     => $minhasFotos,
            'maxFotosAvaliacao' => AvaliacaoService::MAX_FOTOS,
            'usuario'         => $usuario,
            'podeAvaliar'     => $ehComum,
            'minhaAvaliacao'  => $minhaNota,
            'ehOProprio'      => $usuario && (int) $usuario->id === (int) $profissional->id,
            'motivosDenuncia' => self::MOTIVOS_DENUNCIA,
        ]);
    }

    public function avaliar(string $slug)
    {
        $profissional = $this->busca->perfilPorSlug($slug);

        if ($profissional === null) {
            throw PageNotFoundException::forPageNotFound('Profissional não encontrado.');
        }

        $destino = base_url('profissionais/' . $profissional->slug) . '#avaliacoes';

        $rules = [
            'nota'       => 'required|in_list[1,2,3,4,5]',
            'comentario' => 'permit_empty|max_length[1000]',
        ];

        $mensagens = [
            'nota' => [
                'required' => 'Escolha uma nota de 1 a 5 estrelas.',
                'in_list'  => 'Escolha uma nota de 1 a 5 estrelas.',
            ],
            'comentario' => [
                'max_length' => 'O comentário pode ter no máximo 1000 caracteres.',
            ],
        ];

        if (!$this->validate($rules, $mensagens)) {
            return redirect()->to($destino)->with('error', implode(' ', $this->validator->getErrors()));
        }

        $usuarioId  = (int) session('usuario')->id;
        $comentario = trim((string) $this->request->getPost('comentario'));

        $model = model(AvaliacoesModel::class);

        $existente = $model
            ->where('profissional_id', $profissional->id)
            ->where('usuario_comum_id', $usuarioId)
            ->first();

        // Fotos: confere tudo antes de gravar qualquer coisa.
        $servicoAvaliacao = new AvaliacaoService();
        $imagens          = new ImagemUploadService();

        $novasFotos = array_values(array_filter(
            (array) $this->request->getFileMultiple('fotos'),
            static fn ($f) => $f->getError() !== UPLOAD_ERR_NO_FILE
        ));

        foreach ($novasFotos as $i => $arquivo) {
            $erroFoto = $imagens->erroDoArquivo($arquivo, 'A foto ' . ($i + 1));

            if ($erroFoto !== null) {
                return redirect()->to($destino)->with('error', $erroFoto);
            }
        }

        $fotosAtuais = $existente ? $servicoAvaliacao->fotosDaAvaliacao((int) $existente->id) : [];
        $idsAtuais   = array_map(static fn ($f) => (int) $f->id, $fotosAtuais);
        $remover     = array_values(array_intersect(array_map('intval', (array) $this->request->getPost('remover_fotos')), $idsAtuais));

        if (count($fotosAtuais) - count($remover) + count($novasFotos) > AvaliacaoService::MAX_FOTOS) {
            return redirect()->to($destino)->with('error', 'Cada avaliação pode ter no máximo ' . AvaliacaoService::MAX_FOTOS . ' fotos.');
        }

        $dados = [
            'profissional_id'  => (int) $profissional->id,
            'usuario_comum_id' => $usuarioId,
            'nota'             => (int) $this->request->getPost('nota'),
            'comentario'       => $comentario !== '' ? $comentario : null,
        ];

        try {
            if ($existente) {
                $model->update($existente->id, $dados);
                $avaliacaoId = (int) $existente->id;
                $mensagem    = 'Sua avaliação foi atualizada.';
            } else {
                $avaliacaoId = (int) $model->insert($dados);
                $mensagem    = 'Obrigado! Sua avaliação foi publicada.';
            }

            $servicoAvaliacao->removerFotos($avaliacaoId, $remover);

            if (!empty($novasFotos)) {
                $salvas = $servicoAvaliacao->adicionarFotos($avaliacaoId, $novasFotos);

                if ($salvas < count($novasFotos)) {
                    $mensagem .= ' Algumas fotos não puderam ser salvas.';
                }
            }
        } catch (\Throwable $e) {
            log_message('error', '[Avaliacao] Erro ao salvar avaliação: {error}', ['error' => $e->getMessage()]);

            return redirect()->to($destino)->with('error', 'Não foi possível salvar sua avaliação. Tente novamente.');
        }

        return redirect()->to($destino)->with('success', $mensagem);
    }

    public function denunciar(string $slug)
    {
        $profissional = $this->busca->perfilPorSlug($slug);

        if ($profissional === null) {
            throw PageNotFoundException::forPageNotFound('Profissional não encontrado.');
        }

        $destino   = base_url('profissionais/' . $profissional->slug);
        $usuarioId = (int) session('usuario')->id;

        // O que está sendo denunciado: o perfil (padrão), uma avaliação ou uma foto da galeria.
        $avaliacaoId = (int) $this->request->getPost('avaliacao_id');
        $galeriaId   = (int) $this->request->getPost('galeria_id');

        if ($avaliacaoId > 0 && $galeriaId > 0) {
            return redirect()->to($destino)->with('error', 'Escolha uma única coisa para denunciar.');
        }

        $alvo = 'perfil';

        if ($avaliacaoId > 0) {
            $alvo      = 'avaliação';
            $avaliacao = model(AvaliacoesModel::class)->where('id', $avaliacaoId)->where('profissional_id', $profissional->id)->first();

            if (!$avaliacao) {
                return redirect()->to($destino)->with('error', 'Avaliação não encontrada.');
            }

            if ((int) $avaliacao->usuario_comum_id === $usuarioId) {
                return redirect()->to($destino)->with('error', 'Você não pode denunciar a sua própria avaliação.');
            }
        } elseif ($galeriaId > 0) {
            $alvo = 'foto';
            $foto = model(GaleriaModel::class)->where('id', $galeriaId)->where('profissional_id', $profissional->id)->first();

            if (!$foto) {
                return redirect()->to($destino)->with('error', 'Foto não encontrada.');
            }

            if ($usuarioId === (int) $profissional->id) {
                return redirect()->to($destino)->with('error', 'Você não pode denunciar as próprias fotos.');
            }
        } elseif ($usuarioId === (int) $profissional->id) {
            return redirect()->to($destino)->with('error', 'Você não pode denunciar o próprio perfil.');
        }

        $rules = [
            'motivo'    => 'required|in_list[' . implode(',', array_keys(self::MOTIVOS_DENUNCIA)) . ']',
            'descricao' => 'permit_empty|max_length[2000]',
        ];

        $mensagens = [
            'motivo' => [
                'required' => 'Selecione o motivo da denúncia.',
                'in_list'  => 'Selecione o motivo da denúncia.',
            ],
            'descricao' => [
                'max_length' => 'A descrição pode ter no máximo 2000 caracteres.',
            ],
        ];

        if (!$this->validate($rules, $mensagens)) {
            return redirect()->to($destino)->with('error', implode(' ', $this->validator->getErrors()));
        }

        $motivo    = (string) $this->request->getPost('motivo');
        $descricao = trim((string) $this->request->getPost('descricao'));

        if ($motivo === 'OUTRO' && mb_strlen($descricao) < 10) {
            return redirect()->to($destino)->with('error', 'Para "Outro motivo", descreva o que aconteceu (mínimo de 10 caracteres).');
        }

        $model = model(DenunciasModel::class);

        $consultaDuplicada = $model
            ->where('profissional_id', $profissional->id)
            ->where('usuario_id', $usuarioId)
            ->whereIn('status', ['PENDENTE', 'EM_ANALISE']);

        // Cada alvo tem a sua própria denúncia em aberto (denunciar uma avaliação não bloqueia denunciar o perfil).
        if ($avaliacaoId > 0) {
            $consultaDuplicada->where('avaliacao_id', $avaliacaoId);
        } elseif ($galeriaId > 0) {
            $consultaDuplicada->where('galeria_imagem_id', $galeriaId);
        } else {
            $consultaDuplicada->where('avaliacao_id', null)->where('galeria_imagem_id', null);
        }

        if ($consultaDuplicada->first()) {
            return redirect()->to($destino)->with('success', 'Você já denunciou este conteúdo. Nossa equipe está analisando.');
        }

        try {
            $model->insert([
                'profissional_id'   => (int) $profissional->id,
                'usuario_id'        => $usuarioId,
                'avaliacao_id'      => $avaliacaoId > 0 ? $avaliacaoId : null,
                'galeria_imagem_id' => $galeriaId > 0 ? $galeriaId : null,
                'motivo'          => $motivo,
                'descricao'       => $descricao !== '' ? $descricao : null,
                'status'          => 'PENDENTE',
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Denuncia] Erro ao registrar denúncia: {error}', ['error' => $e->getMessage()]);

            return redirect()->to($destino)->with('error', 'Não foi possível enviar a denúncia. Tente novamente.');
        }

        return redirect()->to($destino)->with('success', 'Denúncia enviada. Obrigado por ajudar a manter a comunidade segura.');
    }
}
