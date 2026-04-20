<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('index', 'Home::index');
$routes->get('noticias/cargarMas/(:num)', 'Home::cargarMas/$1');
$routes->get('/noticias/(:num)', 'Home::ver/$1');

$routes->get('/registro', 'Usuario::registro');
$routes->post('/registro', 'Usuario::crear');

$routes->get('activar-cuenta/(:any)', 'Usuario::activarCuenta/$1');

$routes->get('password-request', 'Usuario::linkRequestForm');
$routes->post('password-email', 'Usuario::enviarEmailReset');
$routes->get('reset-password/(:any)', 'Usuario::resetForm/$1');
$routes->post('password/resetForm', 'Usuario::resetPasswordForm');

$routes->get('/login', 'Login::login');
$routes->post('/auth', 'Login::auth');
$routes->get('/logout', 'Login::logout');

$routes->get('/panel', 'Panel::panel', ['filter' => 'auth']);

$routes->get('/noticias/crear', 'Panel::crearNoticia', ['filter' => 'auth']);
$routes->post('/noticias/guardar', 'Panel::guardarNoticiaForm', ['filter' => 'auth']);
$routes->get('noticias/publicar/(:num)', 'Panel::publicar/$1', ['filter' => 'auth']);
$routes->get('noticias/editar/(:num)', 'Panel::editar/$1', ['filter' => 'auth']);
$routes->get('noticias/anular/(:num)', 'Panel::anular/$1', ['filter' => 'auth']);
$routes->get('noticias/ver/(:num)', 'Panel::ver/$1', ['filter' => 'auth']);
$routes->get('noticias/corregir/(:num)', 'Panel::corregir/$1', ['filter' => 'auth']);