<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
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

        // --- SIMULASI LOGIN MULTI-ROLE (Belum pakai Database) ---

        // SKENARIO 1: LOGIN SEBAGAI ADMIN (Harus persis "admin")
        if ($username === "admin" && $password === "123") {
            $session->set([
                'username'  => $username,
                'role'      => 'admin', 
                'logged_in' => true
            ]);
            
            // Redirect ke halaman khusus admin
            return redirect()->to(base_url('admin'));

        // SKENARIO 2: LOGIN SEBAGAI MEMBER (Username bebas, yang penting password "123")
        } elseif ($password === "123") {
            $session->set([
                'username'  => $username, // Menyimpan nama apapun yang diketik di form
                'role'      => 'member', 
                'logged_in' => true
            ]);
            
            // Redirect ke halaman khusus member
            return redirect()->to(base_url('member'));

        // JIKA PASSWORD SALAH
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