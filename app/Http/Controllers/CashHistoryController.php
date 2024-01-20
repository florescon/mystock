<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Cash;

class CashHistoryController extends Controller
{
    public function history()
    {
        abort_if(Gate::denies('cash_access'), 403);

        return view('admin.cashhistory.index');
    }

    public function print(Cash $cash)
    {
        $cash->load('sales.customer');
        return view('admin.cashhistory.print', compact('cash'));
    }

}
