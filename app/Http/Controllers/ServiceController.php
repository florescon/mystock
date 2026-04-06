<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\SettingHour;

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

    public function listAttendance()
    {
        return view('admin.service.list-attendance-index');
    }

    public function captureAttendance()
    {
        return view('admin.attendance.capture-attendance-index');
    }

    public function captureAttendanceHour(SettingHour $settinghour)
    {
        return view('admin.attendance.capture-attendance-hour-index', compact('settinghour'));
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
