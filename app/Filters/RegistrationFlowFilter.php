<?php
namespace App\Filters;

use App\Models\EnderecosModel;
use App\Models\ProfissionaisModel;
use App\Models\ProfissionalCategoriaModel;
use App\Models\ProfissionalHorariosModel;
use App\Models\ProfissionalLocaisAtendimentoModel;
use App\Models\UsuariosComunsModel;
use App\Models\UsuariosModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RegistrationFlowFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        /** @var \CodeIgniter\HTTP\IncomingRequest $request */
        $session = session();
        
        // Se NÃO estiver logado, sai do filtro e permite que o fluxo continue
        if (!$session->has('usuario')) {
            return;
        }

        // Se já concluiu tudo e está cacheado na sessão, passa direto
        if ($session->get('registro_completo') === true) {
            return;
        }

        $usuario = $session->get('usuario');

        // Se está bloqueado, desloga para evitar que navegue
        if ($usuario->status === 'BLOQUEADO') {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Sua conta foi bloqueada.');
        }

        // Recuperamos os dados mais recentes do DB
        $usuarioModel = model(UsuariosModel::class);
        $userDB = $usuarioModel->find($usuario->id);

        /** @var object|array $userDB */
        
        // Ignora rotas de API globais caso existam (opcional)
        $isApi = str_starts_with($request->getPath(), 'api/');

        // 1. Checa Email
        if ($userDB->email_verificado_em === null) {
            $allowed = ['logout', 'verificaremail', 'receberemailvalidacao'];
            if (!in_array($request->getPath(), $allowed) && !$isApi) {
                return redirect()->to('/verificaremail');
            }
            return; // Permite ficar na página de verificar email e nas rotas POST relacionadas
        }

        // 2. Checa Perfil (Comum ou Profissional)
        $temPerfil = false;
        if ($userDB->tipo === 'COMUM') {
            $comumModel = model(UsuariosComunsModel::class);
            $temPerfil = (bool) $comumModel->where('id', $userDB->id)->first();
            
            $allowed = ['logout', 'usuariocomum/criarconta', 'register/usuariocomum/criarconta'];
            if (!$temPerfil && !in_array($request->getPath(), $allowed) && !$isApi) {
                return redirect()->to('/usuariocomum/criarconta');
            }
        } elseif ($userDB->tipo === 'PROFISSIONAL') {
            $profissionalModel = model(ProfissionaisModel::class);
            $temPerfil = (bool) $profissionalModel->where('id', $userDB->id)->first();
            
            $allowed = ['logout', 'profissional/criarconta', 'register/profissional/criarconta'];
            if (!$temPerfil && !in_array($request->getPath(), $allowed) && !$isApi) {
                return redirect()->to('/profissional/criarconta');
            }
        }
        if (!$temPerfil) return; // Permite ficar na página de criar perfil

        // 3. Checa Categorias (Apenas Profissional)
        if ($userDB->tipo === 'PROFISSIONAL') {
            $profissionalCategoriaModel = model(ProfissionalCategoriaModel::class);
            $temCategoria = (bool) $profissionalCategoriaModel->where('profissional_id', $userDB->id)->first();
            
            $allowed = ['logout', 'profissional/categorias'];
            if (!$temCategoria && !in_array($request->getPath(), $allowed) && !$isApi) {
                return redirect()->to('/profissional/categorias');
            }
            if (!$temCategoria) return;
        }

        // 4. Checa Endereço (Todos)
        $enderecoModel = model(EnderecosModel::class);
        $temEndereco = (bool) $enderecoModel->where('usuario_id', $userDB->id)->first();
        
        $allowed = ['logout', 'cadastrarendereco'];
        // Permite também as rotas AJAX filhas do formulário de endereço
        $isAddressAjax = str_starts_with($request->getPath(), 'enderecos/filhos/');
        if (!$temEndereco && !in_array($request->getPath(), $allowed) && !$isAddressAjax && !$isApi) {
            return redirect()->to('/cadastrarendereco');
        }
        if (!$temEndereco) return;

        // 5. Checa Locais e Horarios (Apenas Profissional)
        if ($userDB->tipo === 'PROFISSIONAL') {
            // Checa Locais Atendimento
            $locaisAtendimentoModel = model(ProfissionalLocaisAtendimentoModel::class);
            $temLocal = (bool) $locaisAtendimentoModel->where('profissional_id', $userDB->id)->first();
            
            $allowed = ['logout', 'profissional/locaisatendimento'];
            $isAddressAjax = str_starts_with($request->getPath(), 'enderecos/filhos/');

            if (!$temLocal && !in_array($request->getPath(), $allowed) && !$isApi && !$isAddressAjax) {
                return redirect()->to('/profissional/locaisatendimento');
            }
            if (!$temLocal) return;

            // Checa Horarios
            $horariosModel = model(ProfissionalHorariosModel::class);
            $temHorario = (bool) $horariosModel->where('profissional_id', $userDB->id)->first();
            
            $allowed = ['logout', 'profissional/horarios'];
            if (!$temHorario && !in_array($request->getPath(), $allowed) && !$isApi) {
                return redirect()->to('/profissional/horarios');
            }
            if (!$temHorario) return;
        }

        // Se tudo passou, marca na sessão para não validar de novo
        $session->set('registro_completo', true);
        
        // Se ainda estava como PENDENTE, atualizamos para ATIVO
        if ($userDB->status === 'PENDENTE') {
            $usuarioModel->update($userDB->id, ['status' => 'ATIVO']);
            $usuario->status = 'ATIVO';
            $session->set('usuario', $usuario);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
