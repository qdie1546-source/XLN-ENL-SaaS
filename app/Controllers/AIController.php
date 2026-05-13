<?php

namespace App\Controllers;

class AIController extends Controller
{
    public function generate()
    {
        $_SESSION['flash']['error'] = '功能开发中';
        $this->redirect('/dashboard');
    }

    public function doGenerate()
    {
        $_SESSION['flash']['error'] = '功能开发中';
        $this->redirect('/dashboard');
    }

    public function history()
    {
        $_SESSION['flash']['error'] = '功能开发中';
        $this->redirect('/dashboard');
    }
}
