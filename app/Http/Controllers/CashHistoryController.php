<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Cash;
use App\Models\Expense;
use App\Models\SalePayment;
use App\Models\Sale;

class CashHistoryController extends Controller
{
    public function history()
    {
        abort_if(Gate::denies('expense_access'), 403);

        return view('admin.cashhistory.index');
    }

    public function printShort(Cash $cash)
    {
        $cash->load('sale_payments.sale.customer');
        return view('admin.cashhistory.printshort', compact('cash'));
    }

    public function print(Cash $cash)
    {
        $cash->load('sales.customer', 'sale_payments.sale.customer');
        return view('admin.cashhistory.print', compact('cash'));
    }

    public function printActualShort()
    {
        $lastCash = Cash::latest()->first();

        $currentSalePaymentCash = SalePayment::where('payment_method', 'Cash')
            ->withoutCash()
            ->sum('amount');
        $currentSaleOutPaymentCash = SalePayment::where('payment_method', '<>', 'Cash')
            ->withoutCash()
            ->sum('amount');

        $incomesCash = Expense::incomes()->withoutCash()->onlyPaymentCash()->sum('amount');
        $incomesOutCash = Expense::incomes()->withoutCash()->otherPaymentMethod()->sum('amount');
        $expensesCash = Expense::expenses()->withoutCash()->sum('amount');

        $totalCash = $currentSalePaymentCash + $incomesCash - $expensesCash;


        $sale_payments = SalePayment::query()->withoutCash()->get();
        $expenses = Expense::query()->withoutCash()->get();

        $currentOutPaymentCash = $currentSaleOutPaymentCash + $incomesOutCash;

        return view('admin.cashhistory.actualshort', compact('lastCash','totalCash', 'currentOutPaymentCash', 'expenses', 'sale_payments'));
    }

    public function printActual()
    {
        $lastCash = Cash::latest()->first();

        $currentSalePaymentCash = SalePayment::where('payment_method', 'Cash')
            ->withoutCash()
            ->sum('amount');
        $currentSaleOutPaymentCash = SalePayment::where('payment_method', '<>', 'Cash')
            ->withoutCash()
            ->sum('amount');

        $incomesCash = Expense::incomes()->withoutCash()->onlyPaymentCash()->sum('amount');
        $incomesOutCash = Expense::incomes()->withoutCash()->otherPaymentMethod()->sum('amount');
        $expensesCash = Expense::expenses()->withoutCash()->sum('amount');

        $totalCash = $currentSalePaymentCash + $incomesCash - $expensesCash;


        $sales = Sale::query()->withoutCash()->get();
        $sale_payments = SalePayment::query()->withoutCash()->get();
        $expenses = Expense::query()->withoutCash()->get();

        $currentOutPaymentCash = $currentSaleOutPaymentCash + $incomesOutCash;

        return view('admin.cashhistory.actual', compact('lastCash','totalCash', 'currentOutPaymentCash', 'expenses', 'sale_payments', 'sales'));
    }

}
