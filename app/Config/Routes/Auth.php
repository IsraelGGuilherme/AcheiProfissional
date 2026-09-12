<?php

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