<?php

namespace App\Http\Livewire\Income;

use App\Models\Warehouse;
use Livewire\Component;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Edit extends Component
{
    use LivewireAlert;

    public $listeners = [
        'editModal',
    ];

    /** @var bool */
    public $editModal = false;

    /** @var mixed */
    public $income;

    /** @var array */
    protected $rules = [
        'income.reference'    => 'required|string|max:255',
        'income.category_id'  => 'required|integer|exists:expense_categories,id',
        'income.date'         => 'required|date',
        'income.details'      => 'nullable|string|max:255',
        'income.warehouse_id' => 'nullable',
    ];

    protected $messages = [
        'income.name.required'        => 'The name field cannot be empty.',
        'income.category_id.required' => 'The category field cannot be empty.',
        'income.date.required'        => 'The date field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function getExpenseCategoriesProperty()
    {
        return ExpenseCategory::select('name', 'id')->get();
    }

    public function getWarehousesProperty()
    {
        return Warehouse::select('name', 'id')->get();
    }

    public function render()
    {
        return view('livewire.income.edit');
    }

    public function editModal(Expense $income): void
    {
        abort_if(Gate::denies('income_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->income = Expense::find($income->id);

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->income->save();

        $this->alert('success', __('Income updated successfully.'));

        $this->emit('refreshIndex');

        $this->editModal = false;
    }}
