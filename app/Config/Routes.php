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

// ---> INI RUTE BARU UNTUK HALAMAN DETAIL BUKU <---
$routes->get('katalog/detail/(:num)', 'Home::detail_buku/$1', ['filter' => 'auth']);


// 4. Rute Aksi Persetujuan Peminjaman (Admin - Mengarah ke Home Controller)
$routes->get('admin/peminjaman/acc/(:num)', 'Home::acc_peminjaman/$1', ['filter' => 'auth']);
$routes->get('admin/peminjaman/tolak/(:num)', 'Home::tolak_peminjaman/$1', ['filter' => 'auth']);
$routes->get('admin/live_peminjaman', 'Home::live_peminjaman', ['filter' => 'auth']);  

// =======================================================================
// 5. Grup Rute Admin (Manajemen Data Master & Fisik)
// =======================================================================
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
    $routes->get('eksemplar/edit/(:num)', 'Admin\Eksemplar::edit/$1');
    $routes->post('eksemplar/update/(:num)', 'Admin\Eksemplar::update/$1');
    $routes->get('eksemplar/delete/(:num)', 'Admin\Eksemplar::delete/$1');

    // --- Manajemen Anggota ---
    $routes->get('anggota', 'Admin\Anggota::index');
    $routes->get('anggota/detail/(:num)', 'Admin\Anggota::detail/$1');
    $routes->get('anggota/delete/(:num)', 'Admin\Anggota::delete/$1');

    // --- Manajemen Peminjaman (Admin) ---
    $routes->get('peminjaman', 'Admin\Peminjaman::index');

    // --- Manajemen Pengembalian (Admin) ---
    $routes->get('pengembalian', 'Admin\Pengembalian::index');
    $routes->get('pengembalian/proses/(:num)', 'Admin\Pengembalian::proses/$1');

    // --- Manajemen Denda (Admin) ---
    $routes->get('denda', 'Admin\Denda::index');
    $routes->get('denda/lunas/(:num)', 'Admin\Denda::lunas/$1');

    // --- Manajemen Laporan (Admin) ---
    $routes->get('laporan', 'Admin\Laporan::index');
    $routes->get('laporan/cetak/(:any)', 'Admin\Laporan::cetak/$1');

    // --- Pencarian Otomatis Buku via API ---
    $routes->get('buku/cari_api/(:segment)', 'Admin\Buku::cariBukuViaApi/$1');

    $routes->get('api-docs', 'Home::api_docs');

}); // <--- PENUTUP GRUP ADMIN SEKARANG BENAR DI SINI


// =======================================================================
// 6. WEBSERVICE SERVER ENDPOINTS (Berdiri Sendiri, Aman dari 404)
// =======================================================================
$routes->group('api', ['filter' => 'api_auth'], function($routes) {
    $routes->get('books', 'Api\Books::index');
    $routes->get('books/(:any)', 'Api\Books::show/$1');
    $routes->get('availability/(:num)', 'Api\Books::availability/$1');
});

$routes->get('reminder-proses', 'ReminderController::proses');