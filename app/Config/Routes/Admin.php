<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('admin', 'Admin\Admin::index');

$routes->post('admin/bloqueio/(:num)', 'Admin\Admin::bloqueio_user');