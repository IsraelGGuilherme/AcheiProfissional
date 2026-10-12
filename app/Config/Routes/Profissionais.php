<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Páginas públicas
$routes->get('quemsomos', 'Public\QuemSomos::index');
$routes->get('profissionais/(:segment)', 'Public\Profissionais::ver/$1');

// Ações que exigem login (avaliar: só usuário comum)
$routes->post('profissionais/(:segment)/avaliar', 'Public\Profissionais::avaliar/$1', ['filter' => ['csrf', 'auth:COMUM']]);
$routes->post('profissionais/(:segment)/denunciar', 'Public\Profissionais::denunciar/$1', ['filter' => ['csrf', 'auth']]);
