<?php

namespace App\Http\Livewire\Cash;

use Livewire\Component;
use App\Models\Cash;
use App\Models\Sale;
use App\Models\Expense;

class Index extends Component
{
    public $lastCash;
    public $startDate;
    public $endDate;

    public function mount(): void
    {
        $this->lastCash = Cash::latest()->first();

        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->startDate = now()->startOfYear()->format('Y-m-d');
        $this->endDate = now()->endOfDay()->format('Y-m-d');
    }

    public function render()
    {

        $salesQuery = Sale::with(['customer', 'user', 'saleDetails', 'salepayments', 'saleDetails.product', 'saleDetailsService.service'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate.' 23:59:59'])
            ;

        $sales = $salesQuery->paginate($this->perPage);


        $ExpensesQuery = Expense::with(['category', 'user', 'warehouse'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate.' 23:59:59'])
            ;
        $expenses = $ExpensesQuery->paginate($this->perPage);

        return view('livewire.cash.index', compact('sales', 'expenses'));
    }
}
