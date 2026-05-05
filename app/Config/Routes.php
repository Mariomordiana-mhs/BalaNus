<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Arahkan rute default ke halaman login
$routes->get('/', 'AuthController::index');

// Rute untuk proses Login
$routes->get('/login', 'AuthController::index');
$routes->post('/login-process', 'AuthController::auth');

// Rute untuk proses Register
$routes->get('/register', 'AuthController::register');
$routes->post('/register-process', 'AuthController::saveRegister');

// Rute untuk Logout
$routes->get('/logout', 'AuthController::logout');

// Rute untuk Halaman Multi-Role (Setelah berhasil login)
$routes->get('/admin', 'Home::admin');
$routes->get('/member', 'Home::member');