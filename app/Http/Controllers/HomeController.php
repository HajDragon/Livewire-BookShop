<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public $titleDashboard = 'This comes from dashboard controller';

    public function index()
    {
        return view('dashboard', [
            'titleDashboard' => $this->titleDashboard,

        ]);
    }
}
