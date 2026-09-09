<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Visual::busca');
$routes->get('busca', 'Visual::busca');
$routes->get('login', 'Visual::login');
$routes->get('cadastro', 'Visual::cadastro');
$routes->get('cadastro/usuario', 'Visual::cadastroUsuario');
$routes->get('cadastro/profissional', 'Visual::cadastroProfissional');
$routes->get('cadastro/contratante', 'Visual::cadastroContratante');
$routes->get('recuperar-senha', 'Visual::recuperarSenha');
$routes->get('profissional/(:segment)', 'Visual::perfil/$1');
$routes->get('perfil/contratante', 'Visual::perfilContratante');
$routes->get('perfil/profissional', 'Visual::perfilProfissional');
$routes->get('admin', 'Visual::admin');
$routes->get('admin/categorias', 'Visual::categorias');
$routes->get('admin/denuncias', 'Visual::denuncias');
$routes->get('admin/aprovacoes', 'Visual::aprovacoes');
