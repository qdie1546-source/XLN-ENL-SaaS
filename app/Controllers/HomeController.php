<?php

namespace App\Controllers;

use App\Libraries\Session;

class HomeController extends Controller
{
    public function index()
    {
        if (Session::has('user')) {
            $this->redirect('/dashboard');
        }
        $this->view('home/index');
    }
}
