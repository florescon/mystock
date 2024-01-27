<?php

namespace App\Http\Controllers;

use App\Models\SettingHour;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class SettingHourController extends Controller
{
    public function __invoke()
    {
        abort_if(Gate::denies('setting_access'), 403);

        return view('admin.setting-hour.index');
    }
}
