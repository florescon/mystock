<?php

namespace App\Http\Livewire\Services;

use App\Models\Customer;
use App\Models\Service;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Traits\Datatable;
use App\Http\Livewire\WithSorting;
use Livewire\WithPagination;


class CreateFormat extends Component
{
    use WithPagination;
    use WithSorting;
    use Datatable;

    /** @var mixed */
    public $service;

    public $serviceIds;

    /** @var array<string> */
    public $listeners = [
        'refreshIndex' => '$refresh',
    ];

    /** @var array<array<string>> */
    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

    public $totalSteps = 2;
    public $step = 1;

    //Fields to store
    public $customer_id = '';
    public $isFixed = true;
    public $amount = 0;
    public $hours = 0;
    public $rate = 0;
    public $calculatedAmount = 0;
    public $message = '';

    protected $rules = [
        'selected' => 'required',
        'isFixed' => 'required|boolean',
        'amount' => 'required|numeric|gt:0',
        'hours' => 'numeric|gt:0',
        'rate' => 'numeric|gt:0',
        'message' => 'required|min:20',
    ];

    protected $messages = [
        'customer_id.required' => 'Please select Customer.',
        'amount.required' => 'Please enter Amount.',
        'rate.gt' => 'Hourly Rate must be greater than 0.',
        'message.required' => 'Please add Message for Customer.',
    ];

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 5;
        $this->orderable = (new Service())->orderable;
    }

    public function moveAhead()
    {
        if($this->step == 1) {
            //Validate Step 1 Data
            $this->validateOnly('selected');
            //Wont reach here if the Validation Fails.
            //Reset Error Bag to clear any errors on Step 2. 
            $this->resetErrorBag();
            //Recalculate Amount for Step 2.
            $this->_calculateAmount();
        }

        // if($this->step == 2) {
        //     if($this->isFixed) {
        //         //Fixed Invoice Validation
        //         $this->validateOnly('amount');
        //     } else {
        //         //Hourly Invoice Validation
        //         $this->validateOnly('hours');
        //         $this->validateOnly('rate');
        //     }
        //     //Reset Error Bag to clear any errors on Step 3. 
        //     $this->resetErrorBag(); 
        // }

        if($this->step == 3) {
            $this->validateOnly('message');
            // Save to the Invoice Model

            // $invoice = new Invoice;
            // $invoice->customer_id = $this->customer_id;
            // $invoice->is_fixed = $this->isFixed;
            // if($invoice->is_fixed ) {
            //     $invoice->amount = $this->amount;
            // } else {
            //     $invoice->hours = $this->hours;
            //     $invoice->rate = $this->rate;
            //     $invoice->amount = $this->calculatedAmount;
            // }
            // $invoice->message = $this->message;
            // $invoice->save();

            // redirect
            redirect()->route('dashboard');
        }

        //Increase Step
        $this->step += 1;
        $this->_validateStep();
    }

    public function moveBack()
    {
        $this->step -= 1;
        $this->_validateStep();
    }

    private function _validateStep()
    {
        if ($this->step < 1) {
            $this->step = 1;
        }

        if ($this->step > $this->totalSteps) {
            $this->step = $this->totalSteps;
        }
    }

    public function updatedHours($value)
    {
        //
        $this->calculatedAmount = 0;
        $this->validateOnly('hours');
        $this->_calculateAmount();
    }

    public function updatedRate($value)
    {
        //
        $this->calculatedAmount = 0;
        $this->validateOnly('rate');
        $this->_calculateAmount();

    }

    private function _calculateAmount()
    {
        if (is_numeric($this->hours) && is_numeric($this->rate)) {
            $this->calculatedAmount = $this->hours * $this->rate;
        } else {
            $this->calculatedAmount = 0;
        }
    }
    public function createFormat()
    {
        $serviceGrouped = collect();

        return redirect()->route('format-one.index', urlencode(json_encode($this->selected)));
    }


    public function render()
    {
        $query = Service::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $services = $query->paginate($this->perPage);

        $customers = Customer::orderBy('name')->pluck('name', 'id');
        return view('livewire.services.create-format', [
            'customers' => $customers,
            'services' => $services,
        ]);
    }
}
