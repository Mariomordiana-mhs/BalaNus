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
$routes->get('admin', 'Home::admin', ['filter' => 'auth']);
$routes->get('member', 'Home::member', ['filter' => 'auth']);

// 3. Rute Aksi Peminjaman (Terproteksi)
$routes->get('peminjaman/batal/(:num)', 'Home::batalkan/$1', ['filter' => 'auth']);
$routes->get('peminjaman/detail/(:num)', 'Home::detail/$1', ['filter' => 'auth']);

// Rute untuk halaman Katalog Buku
$routes->get('katalog', 'Home::katalog', ['filter' => 'auth']);

// Rute untuk memproses pengajuan pinjam baru
$routes->get('peminjaman/ajukan/(:num)', 'Home::ajukan/$1', ['filter' => 'auth']);

// Rute untuk melihat semua riwayat peminjaman (Halaman Tabel Lengkap)
$routes->get('peminjaman/saya', 'Home::riwayat_saya', ['filter' => 'auth']);