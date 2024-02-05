<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ServiceController extends Controller
{
    public function index()
    {
        return view('admin.service.index');
    }

    public function inscriptions()
    {
        return view('admin.service.services-inscriptions-index');
    }

    public function monthly()
    {
        return view('admin.service.services-monthly-index');
    }

    public function free()
    {
        return view('admin.service.services-free-index');
    }

    public function freeSwim()
    {
        return view('admin.service.services-free-swim-index');
    }

    public function other()
    {
        return view('admin.service.services-other-index');
    }
}
