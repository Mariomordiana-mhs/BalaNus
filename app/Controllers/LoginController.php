<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LoginController extends BaseController
{
     public function index()
    {
        return view('v_login');
    }

     public function auth()
    {
        $session = session();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // LOGIN SEDERHANA
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

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
