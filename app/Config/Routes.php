<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LoginController::index');

$routes->get('/login', 'LoginController::index');
$routes->post('/login-process', 'LoginController::auth');

$routes->get('/register', 'LoginController::register');
$routes->post('/register-process', 'LoginController::saveRegister');

$routes->get('/homepage', 'Home::homepage');
$routes->get('/logout', 'LoginController::logout');