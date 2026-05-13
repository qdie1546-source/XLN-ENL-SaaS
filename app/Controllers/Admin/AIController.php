<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

class AIController extends Controller
{
    public function config() { $this->redirect('/dashboard'); }
    public function saveConfig() { $this->redirect('/dashboard'); }
}
