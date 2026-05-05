<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LoginController extends BaseController
{
     public function __construct()
    {
        // Memuat helper form dan url
        helper(['form', 'url']);
    }

    // --- 1. MENAMPILKAN HALAMAN LOGIN ---
    public function index()
    {
        // Memanggil file app/Views/v_login.php
        return view('v_login');
    }

    // --- 2. PROSES VALIDASI LOGIN ---
    public function auth()
    {
        $session = session();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // CONTOH SEMENTARA (BELUM MENGGUNAKAN DATABASE)
        // Jika username "admin" dan password "123"
        if ($username === "admin" && $password === "123") {
            
            // Set session data
            $session->set([
                'username'  => $username,
                'logged_in' => true
            ]);
            
            // Redirect ke homepage jika sukses
            return redirect()->to(base_url('homepage'));
            
        } else {
            // Redirect kembali ke login dengan membawa pesan error (Flashdata)
            return redirect()->to(base_url('login'))->with('error', 'Username atau password salah!');
        }
    }

    // --- 3. MENAMPILKAN HALAMAN REGISTER ---
    public function register()
    {
        // Memanggil file app/Views/register.php
        return view('register');
    }

    // --- 4. PROSES SIMPAN DATA REGISTER ---
    public function saveRegister()
    {
        // Mengambil data dari form register.php
        $fullname = $this->request->getPost('fullname');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        /* 
         * LOGIKA DATABASE SEMENTARA DIKOSONGKAN
         * Nanti Anda bisa memasukkan perintah Model untuk Insert/Save ke MySQL di sini.
         */

        // Setelah proses register sukses (simulasi), arahkan ke halaman login
        // dengan membawa pesan sukses (Flashdata)
        return redirect()->to(base_url('login'))->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }

    // --- 5. PROSES LOGOUT ---
    public function logout()
    {
        $session = session();
        $session->destroy(); // Menghapus semua session
        
        // Arahkan kembali ke login dengan pesan
        return redirect()->to(base_url('login'))->with('success', 'Anda telah berhasil logout.');
    }
}
