<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    function __construct()
    {
        helper('form');
    }

    public function auth()
    {
        $session = session();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // CONTOH SEMENTARA (BELUM DATABASE)
        if ($username == "admin" && $password == "123") {

            $session->set([
                'username' => $username,
                'login' => true
            ]);

            return redirect()->to('/homepage');

        } else {
            return redirect()->to('/login')->with('error', 'Username atau password salah');
        }
    }

    // HALAMAN REGISTER
    public function register()
    {
        return view('v_register');
    }

    // PROSES REGISTER
    public function saveRegister()
    {
        $username = $this->request->getPost('username');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // SEMENTARA (BELUM SIMPAN DATABASE)
        return redirect()->to('/login')->with('success', 'Registrasi berhasil, silakan login');
    }

    // LOGOUT
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
