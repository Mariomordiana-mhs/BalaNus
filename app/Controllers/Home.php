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
        
        // Cek apakah belum login ATAU rolenya bukan admin
        if (!$session->get('logged_in') || $session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses ditolak! Anda bukan Admin.');
        }

        // Tampilkan halaman admin
        return view('home_admin'); 
    }

    // --- FUNGSI UNTUK HALAMAN MEMBER ---
    public function member()
    {
        $session = session();
        
        // Cek apakah belum login ATAU rolenya bukan member
        if (!$session->get('logged_in') || $session->get('role') !== 'member') {
            return redirect()->to('/login')->with('error', 'Akses ditolak! Anda bukan Member.');
        }

        // Tampilkan halaman member
        return view('home_member');
    }
}
