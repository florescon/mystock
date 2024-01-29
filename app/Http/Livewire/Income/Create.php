<?php

namespace App\Http\Livewire\Income;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['createModal'];

    public $createModal = false;

    /** @var mixed */
    public $income;

    protected $rules = [
        'income.reference'    => 'required|string|max:255',
        'income.category_id'  => 'required|integer|exists:expense_categories,id',
        'income.date'         => 'required|date',
        'income.amount'       => 'required|numeric|min:1',
        'income.details'      => 'nullable|string|min:3',
        'income.user_id'      => 'nullable',
        'income.warehouse_id' => 'nullable',
        'income.is_expense' => 'required',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        abort_if(Gate::denies('expense_access'), 403);

        return view('livewire.income.create');
    }

    public function createModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->income = new Expense();

        $this->income->is_expense = false;

        $this->income->date = date('Y-m-d');

        $this->createModal = true;
    }

    public function create(): void
    {
        try {
            $validatedData = $this->validate();

            $this->income->save($validatedData);

            $this->income->user()->associate(auth()->user());

            $this->alert('success', __('Income created successfully.'));

            $this->emit('refreshIndex');

            $this->createModal = false;
        } catch (Throwable $th) {
            $this->alert('error', $th->getMessage());
        }
    }

    public function getExpenseCategoriesProperty()
    {
        return ExpenseCategory::select('name', 'id')->get();
    }

    public function getWarehousesProperty()
    {
        return Warehouse::select('name', 'id')->get();
    }
}
