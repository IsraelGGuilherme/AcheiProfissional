<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Login
$routes->get('login', 'Auth\Login::index');
$routes->post('login', 'Auth\Login::login');

// Logout
$routes->get('logout', 'Auth\Login::logout');

// Register
$routes->get('register', 'Auth\Register::index');
$routes->get('register/usuario', 'Auth\Register::registerUsuarioComum');
$routes->get('register/profissional', 'Auth\Register::registerProfissional');
$routes->post('register', 'Auth\Register::register');
$routes->post('register/admin', 'Auth\Register::register_admin');

// Validação de email
$routes->get('verificaremail', 'Auth\Register::formValidarEmail');
$routes->post('receberemailvalidacao', 'Auth\Register::gerarCodigoValidacaoEmail');
$routes->post('verificaremail', 'Auth\Register::validarCodigoValidacaoEmail');

// Recuperação de conta (Esqueci minha senha)
$routes->get('esqueciminhasenha', 'Auth\Login::formEsqueciMinhaSenha');
$routes->post('esqueciminhasenha', 'Auth\Login::esqueciMinhaSenha');
$routes->get('recuperarconta', 'Auth\Login::formRecuperarConta');
$routes->post('receberemailrecuperacao', 'Auth\Login::gerarCodigoRecuperacao');
$routes->post('recuperarconta', 'Auth\Login::validarCodigoRecuperacao');
$routes->get('redefinirsenha', 'Auth\Login::formRedefinirSenha');
$routes->post('redefinirsenha', 'Auth\Login::redefinirSenha');

// Em desenvolvimento
$routes->get('usuariocomum/criarconta', 'Auth\Register::registerUsuarioComumForm');
$routes->get('profissional/criarconta', 'Auth\Register::registerProfissionalForm');
$routes->post('register/usuariocomum/criarconta', 'Auth\Register::registerUsuarioComumDados');
$routes->post('register/profissional/criarconta', 'Auth\Register::registerProfissionalDados');
    
// Endereços
$routes->get('cadastrarendereco', 'Auth\Enderecos::formCadastrarEndereco');
$routes->post('cadastrarendereco', 'Auth\Enderecos::cadastrarEndereco');
$routes->get('enderecos/filhos/(:num)', 'Auth\Enderecos::getFilhos/$1');

// Categorias
$routes->get('profissional/categorias', 'Auth\Categorias::formCategorias');
$routes->post('profissional/categorias', 'Auth\Categorias::cadastrarCategorias');

// Locais de Atendimento
$routes->get('profissional/locaisatendimento', 'Auth\LocaisAtendimento::formLocaisAtendimento');
$routes->post('profissional/locaisatendimento', 'Auth\LocaisAtendimento::cadastrarLocais');

// Horários de Atendimento
$routes->get('profissional/horarios', 'Auth\HorariosAtendimento::formHorariosAtendimento');
$routes->post('profissional/horarios', 'Auth\HorariosAtendimento::cadastrarHorarios');