<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Rederiza a tela principal de admin
$routes->get('admin', 'Admin\Admin::index');

// lista de administradores
$routes->get('admin/administradores', 'Auth\Administradores::index');

// formulário para criar administradores
$routes->get('admin/administradores/novo', 'Auth\Administradores::form_admin');

// formulário para criar administradores
$routes->get('admin/administradores/editar/(:num)', 'Auth\Administradores::form_admin_data/$1');

// formulário para criar administradores
$routes->get('admin/administradores/excluir/(:num)', 'Auth\Administradores::form_admin_data/$1');

// Rota para inserir um usuário admin no banco de dados
$routes->post('admin/administradores/inserir', 'Auth\Administradores::inserir');

// Rota para update um usuário admin no banco de dados
$routes->post('admin/administradores/editar', 'Auth\Administradores::editar');

// Rota para excluir um usuário admin no banco de dados
$routes->post('admin/administradores/excluir', 'Auth\Administradores::excluir');

// Bloqueia um usuário por um tempo determinado
$routes->post('admin/bloqueio/(:num)', 'Admin\Admin::bloqueio_user');

// Exclui um usuário da base de dados
$routes->post('admin/excluir/(:num)', 'Admin\Admin::excluir_usuario/$1');