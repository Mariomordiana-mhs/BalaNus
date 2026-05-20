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

// 3. Rute Aksi Peminjaman & Katalog (Member)
$routes->get('peminjaman/batal/(:num)', 'Home::batalkan/$1', ['filter' => 'auth']);
$routes->get('peminjaman/detail/(:num)', 'Home::detail/$1', ['filter' => 'auth']);
$routes->get('peminjaman/ajukan/(:num)', 'Home::ajukan/$1', ['filter' => 'auth']);
$routes->get('peminjaman/saya', 'Home::riwayat_saya', ['filter' => 'auth']);
$routes->get('katalog', 'Home::katalog', ['filter' => 'auth']);

// 4. Rute Aksi Persetujuan Peminjaman (Admin - Mengarah ke Home Controller)
$routes->get('admin/peminjaman/acc/(:num)', 'Home::acc_peminjaman/$1', ['filter' => 'auth']);
$routes->get('admin/peminjaman/tolak/(:num)', 'Home::tolak_peminjaman/$1', ['filter' => 'auth']);
// Tambahkan di bawah Rute Aksi Persetujuan Peminjaman Admin
$routes->get('admin/live_peminjaman', 'Home::live_peminjaman', ['filter' => 'auth']);

// 5. Grup Rute Admin (Manajemen Data Master & Fisik)
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    
    // --- Manajemen Master Buku ---
    $routes->get('buku', 'Admin\Buku::index'); 
    $routes->get('buku/create', 'Admin\Buku::create');
    $routes->post('buku/store', 'Admin\Buku::store');
    $routes->get('buku/edit/(:num)', 'Admin\Buku::edit/$1');
    $routes->post('buku/update/(:num)', 'Admin\Buku::update/$1');
    $routes->get('buku/delete/(:num)', 'Admin\Buku::delete/$1');

    // --- Manajemen Eksemplar Fisik ---
    $routes->get('eksemplar', 'Admin\Eksemplar::index');
    $routes->get('eksemplar/create', 'Admin\Eksemplar::create');
    $routes->post('eksemplar/store', 'Admin\Eksemplar::store');
    $routes->get('eksemplar/delete/(:num)', 'Admin\Eksemplar::delete/$1');
    
});