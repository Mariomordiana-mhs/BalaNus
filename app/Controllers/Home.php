<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function homepage()
    {
        // CEK LOGIN
        if (!session()->get('login')) {
            return redirect()->to('/login');
        }

        return view('homepage');
    }
}
