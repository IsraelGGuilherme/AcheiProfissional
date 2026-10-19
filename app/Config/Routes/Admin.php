<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Rederiza a tela principal de admin
$routes->get('admin', 'Admin\Admin::index', ['filter' => 'auth:ADMIN']);

// lista de administradores
$routes->get('admin/administradores', 'Auth\Administradores::index', ['filter' => 'auth:ADMIN']);

// formulário para criar administradores
$routes->get('admin/administradores/novo', 'Auth\Administradores::form_admin', ['filter' => 'auth:ADMIN']);

// formulário para criar administradores
$routes->get('admin/administradores/editar/(:num)', 'Auth\Administradores::form_admin_data/$1', ['filter' => 'auth:ADMIN']);

// formulário para criar administradores
$routes->get('admin/administradores/excluir/(:num)', 'Auth\Administradores::form_admin_data/$1', ['filter' => 'auth:ADMIN']);

// Rota para inserir um usuário admin no banco de dados
$routes->post('admin/administradores/inserir', 'Auth\Administradores::inserir', ['filter' => 'auth:ADMIN']);

// Rota para update um usuário admin no banco de dados
$routes->post('admin/administradores/editar', 'Auth\Administradores::editar', ['filter' => 'auth:ADMIN']);

// Rota para excluir um usuário admin no banco de dados
$routes->post('admin/administradores/excluir', 'Auth\Administradores::excluir', ['filter' => 'auth:ADMIN']);

// Bloqueia um usuário por um tempo determinado
$routes->post('admin/bloqueio/(:num)', 'Admin\Admin::bloqueio_user', ['filter' => 'auth:ADMIN']);

// Desbloqueia um usuário bloqueado (mantém o registro de bloqueio como histórico).
$routes->post('admin/desbloqueio/(:num)', 'Admin\Admin::desbloquear_usuario/$1', ['filter' => 'auth:ADMIN']);

// Exclui um usuário da base de dados
$routes->post('admin/excluir/(:num)', 'Admin\Admin::excluir_usuario/$1', ['filter' => 'auth:ADMIN']);


// ---------- CRUD de categorias (somente ADMIN) ----------
$routes->group('admin/categorias', ['filter' => 'auth:ADMIN'], static function ($routes) {
    $routes->get('/', 'Admin\Categorias::index');
    $routes->post('criar', 'Admin\Categorias::criar');
    $routes->post('editar/(:num)', 'Admin\Categorias::editar/$1');
    $routes->post('excluir/(:num)', 'Admin\Categorias::excluir/$1');
});
