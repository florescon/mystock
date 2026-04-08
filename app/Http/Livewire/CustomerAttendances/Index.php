<?php

namespace App\Http\Livewire\CustomerAttendances;

use Livewire\Component;
use App\Models\Customer;

class Index extends Component
{
    public Customer $customer;    

    public bool $width = TRUE;
    public bool $prices = false;
    public bool $priceIVaIncluded = false;
    public bool $breakdown = false;
    public bool $general = false;
    public bool $details = true;

    protected $queryString = [
        'width' => ['except' => FALSE],
        'prices' => ['except' => FALSE],
        'priceIVaIncluded' => ['except' => FALSE],
        'breakdown' => ['except' => FALSE],
        'general' => ['except' => FALSE],
        'details' => ['except' => FALSE],
    ];

    public function render()
    {
        return view('livewire.customer-attendances.index');
    }
}
