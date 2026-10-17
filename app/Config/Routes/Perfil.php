<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Área logada: "Meu perfil". Todos os POSTs passam por CSRF.
$routes->get('perfil', 'Perfil\Inicio::index', ['filter' => 'auth']);

// Profissional
$routes->get('perfil/profissional', 'Perfil\Profissional::index', ['filter' => 'auth:PROFISSIONAL']);
$routes->post('perfil/profissional', 'Perfil\Profissional::salvarDados', ['filter' => ['csrf', 'auth:PROFISSIONAL']]);
$routes->post('perfil/profissional/categorias', 'Perfil\Profissional::salvarCategorias', ['filter' => ['csrf', 'auth:PROFISSIONAL']]);
$routes->post('perfil/profissional/endereco', 'Perfil\Profissional::salvarEnderecoPerfil', ['filter' => ['csrf', 'auth:PROFISSIONAL']]);
$routes->post('perfil/profissional/locais', 'Perfil\Profissional::adicionarLocal', ['filter' => ['csrf', 'auth:PROFISSIONAL']]);
$routes->post('perfil/profissional/locais/(:num)/remover', 'Perfil\Profissional::removerLocal/$1', ['filter' => ['csrf', 'auth:PROFISSIONAL']]);

// Galeria de trabalhos do profissional
$routes->post('perfil/profissional/galeria', 'Perfil\\Galeria::adicionar', ['filter' => ['csrf', 'auth:PROFISSIONAL']]);
$routes->post('perfil/profissional/galeria/(:num)/legenda', 'Perfil\\Galeria::legenda/$1', ['filter' => ['csrf', 'auth:PROFISSIONAL']]);
$routes->post('perfil/profissional/galeria/(:num)/remover', 'Perfil\\Galeria::remover/$1', ['filter' => ['csrf', 'auth:PROFISSIONAL']]);

// Contratante (usuário comum)
$routes->get('perfil/contratante', 'Perfil\Contratante::index', ['filter' => 'auth:COMUM']);
$routes->post('perfil/contratante', 'Perfil\Contratante::salvarDados', ['filter' => ['csrf', 'auth:COMUM']]);
$routes->post('perfil/contratante/endereco', 'Perfil\Contratante::salvarEnderecoPerfil', ['filter' => ['csrf', 'auth:COMUM']]);

// Exclusão da própria conta (qualquer tipo de usuário logado)
$routes->post('perfil/excluir-conta', 'Perfil\Conta::excluir', ['filter' => ['csrf', 'auth']]);
