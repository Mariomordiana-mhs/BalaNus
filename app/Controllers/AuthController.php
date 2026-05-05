<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel; // Wajib memanggil Model untuk akses database

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
        return view('v_login');
    }

    // --- 2. PROSES VALIDASI LOGIN (Dengan Database & Multi-Role) ---
    public function auth()
    {
        $session = session();
        $userModel = new UserModel();
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Cari data user berdasarkan username di database
        $user = $userModel->where('username', $username)->first();

        // Jika user ditemukan di database
        if ($user) {
            // Verifikasi password yang diketik di form dengan password hash di database
            if (password_verify((string)$password, $user['password'])) {
                
                // Jika password benar, set data ke dalam session
                $session->set([
                    'id_user'   => $user['id_user'],
                    'username'  => $user['username'],
                    'role'      => $user['role'], // Mengambil role asli dari database ('admin' atau 'user')
                    'logged_in' => true
                ]);
                
                // PENGECEKAN ROLE UNTUK REDIRECT
                if ($user['role'] === 'admin') {
                    // Jika rolenya admin, arahkan ke rute /admin
                    return redirect()->to(base_url('admin'));
                } else {
                    // Jika rolenya user (atau selain admin), arahkan ke rute /member
                    return redirect()->to(base_url('member'));
                }
                
            } else {
                // Jika password salah
                return redirect()->to(base_url('login'))->with('error', 'Password salah!');
            }
        } else {
            // Jika username tidak ada di database
            return redirect()->to(base_url('login'))->with('error', 'Username tidak ditemukan!');
        }
    }

    // --- 3. MENAMPILKAN HALAMAN REGISTER ---
    public function register()
    {
        return view('register');
    }

    // --- 4. PROSES SIMPAN DATA REGISTER (Simpan ke Database) ---
    public function saveRegister()
    {
        $userModel = new UserModel();

        // Aturan validasi (Pastikan tidak ada username atau email yang kembar)
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[3]'
        ];

        // Jika validasi gagal (misal email sudah dipakai orang lain)
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('register'))->withInput()->with('error', 'Data tidak valid atau username/email sudah terdaftar.');
        }

        // Siapkan data yang akan di-insert ke database
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            // Password WAJIB di-hash demi keamanan
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'member' // Setiap yang mendaftar via form otomatis menjadi 'member' biasa
        ];

        // Eksekusi simpan ke database
        $userModel->insert($data);

        // Arahkan ke halaman login dengan membawa pesan sukses
        return redirect()->to(base_url('login'))->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }

    // --- 5. PROSES LOGOUT ---
    public function logout()
    {
        $session = session();
        $session->destroy(); // Hancurkan semua data session (keluar sistem)
        
        // Arahkan kembali ke login
        return redirect()->to(base_url('login'))->with('success', 'Anda telah berhasil logout.');
    }
}