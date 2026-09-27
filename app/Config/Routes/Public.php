<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Página inicial
$routes->get('/', 'Public\Home::index');