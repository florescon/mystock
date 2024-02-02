<?php

namespace App\Http\Livewire\Cash;

use Livewire\Component;
use App\Models\Cash;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use LivewireAlert;

    public $startDate;
    public $endDate;

    public $initial;

    public $cash;
    public $cashGet;

    /** @var array<string> */
    public $listeners = [
        'CheckOutCash',
        'refreshIndex' => '$refresh',
    ];

    /** @var array */
    protected $rules = [
        'cashGet.initial'        => 'required|integer||min:1',
        'cashGet.title' => 'nullable|string|min:3|max:255',
    ];

    public function mount(): void
    {
        $this->cash = Cash::latest()->first();

        $this->cashGet = !optional($this->cash)->is_processed ? $this->cash : null; 

        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->startDate = now()->startOfYear()->subYear()->format('Y-m-d');
        $this->endDate = now()->endOfDay()->format('Y-m-d');
    }

    public function saveInitialCash()
    {
        $this->validate();

        $lastCash = Cash::latest()->first();

        if(($lastCash == null) || ($lastCash->is_processed == true)){
            Cash::create([
                'initial'       => $this->cashGet['initial'], 
                'title'       => $this->cashGet['title'] ?? '', 
                'user_id'       => Auth::user()->id,
            ]);
        }
        else{
            $lastCash->update([
                'initial' => $this->cashGet['initial'],
                'title'       => $this->cashGet['title'] ?? '', 
            ]);
        }

        $this->emit('refreshIndex');

        $this->alert('success', __('Initial Cash updated successfully!'));
    }

    public function CheckOutCash()
    {
        $lastCash = Cash::latest()->first();

        $lastCash::update([
            'user_id'             => Auth::user()->id,
        ]);

        $sales = Sale::query()->withoutCash()->get();
        foreach($sales as $sale){
            $sale->update(['cash_id' => $cash->id]);
        }

        $salesPayment = SalePayment::query()->withoutCash()->get();
        foreach($salesPayment as $SalePayment){
            $SalePayment->update(['cash_id' => $cash->id]);
        }

        $finances = Expense::query()->withoutCash()->get();
        foreach($finances as $finance){
            $finance->update(['cash_id' => $cash->id]);
        }

        return redirect()->route('cash.index');
    }

    public function render()
    {
        $salesQueryCash = Sale::with(['customer', 'user', 'saleDetails', 'salepayments', 'saleDetails.product', 'saleDetailsService.service'])
            ->where('payment_method', 'Cash')->withoutCash();
        $salesCash = $salesQueryCash->paginate(8 ,['*'],'sales');

        $salesQueryOutCash = Sale::with(['customer', 'user', 'saleDetails', 'salepayments', 'saleDetails.product', 'saleDetailsService.service'])
            ->where('payment_method', '<>', 'Cash')->withoutCash();
        $salesOutCash = $salesQueryOutCash->paginate(8 ,['*'],'sales');

        $ExpensesQuery = Expense::with(['category', 'user', 'warehouse'])->withoutCash()->expenses();
        $expenses = $ExpensesQuery->paginate(5 ,['*'],'expenses');

        $IncomesQuery = Expense::with(['category', 'user', 'warehouse'])->withoutCash()->incomes();
        $incomes = $IncomesQuery->paginate(5 ,['*'],'incomes');

        $currentSalePaymentCash = SalePayment::where('payment_method', 'Cash')
            ->withoutCash()
            ->sum('amount');
        $currentSaleOutPaymentCash = SalePayment::where('payment_method', '<>', 'Cash')
            ->withoutCash()
            ->sum('amount');

        $incomesCash = Expense::incomes()->withoutCash()->sum('amount');
        $expensesCash = Expense::expenses()->withoutCash()->sum('amount');

        $totalCash = $currentSalePaymentCash + $incomesCash - $expensesCash;

        $lastCash = Cash::latest()->first();

        return view('livewire.cash.index', compact('salesCash', 'salesOutCash', 'expenses', 'incomes', 'totalCash', 'currentSaleOutPaymentCash', 'lastCash'));
    }
}
