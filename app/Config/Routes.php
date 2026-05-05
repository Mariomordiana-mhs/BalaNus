<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1. Rute Publik (Bisa diakses siapa saja)
$routes->get('/', 'AuthController::index');
$routes->get('login', 'AuthController::index');
$routes->post('login-process', 'AuthController::auth');

$routes->get('register', 'AuthController::register');
$routes->post('register-process', 'AuthController::saveRegister');

$routes->get('logout', 'AuthController::logout');

// 2. Rute Terproteksi (Hanya bisa diakses jika sudah Login)
// Kita tambahkan ['filter' => 'auth'] agar aman
$routes->get('admin', 'Home::admin', ['filter' => 'auth']);
$routes->get('member', 'Home::member', ['filter' => 'auth']);