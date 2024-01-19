<?php

namespace App\Exports;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class IncomeExport implements FromView
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    public function query()
    {

        if ($this->models) {
            return Expense::query()->whereIn('id', $this->models)->datesForPeriod('this year')->incomes();
        }

        return Expense::query()->datesForPeriod('this year')->incomes();
    }

    public function view(): View
    {
        return view('pdf.incomes', [
            'data' => $this->query()->get(),
        ]);
    }
}
