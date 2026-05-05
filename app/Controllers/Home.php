<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    // --- FUNGSI UNTUK HALAMAN ADMIN ---
    public function admin()
    {
        $session = session();
        
        // Cek apakah belum login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // Cek apakah rolenya BUKAN admin
        if ($session->get('role') !== 'admin') {
            // Jika user biasa mencoba masuk admin, kembalikan ke halaman member
            return redirect()->to('/member')->with('error', 'Akses ditolak! Anda bukan Admin.');
        }

        // Tampilkan halaman admin
        return view('home_admin'); 
    }

    // --- FUNGSI UNTUK HALAMAN MEMBER ---
    public function member()
    {
        $session = session();
        
        // Cek apakah belum login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // PENTING: Cek role menggunakan 'user' sesuai data di database
        if ($session->get('role') !== 'member') {
            // Jika admin mencoba masuk member, kembalikan ke halaman admin
            return redirect()->to('/admin')->with('error', 'Halaman ini khusus pengguna biasa.');
        }

        // Tampilkan halaman member
        return view('home_member');
    }
}