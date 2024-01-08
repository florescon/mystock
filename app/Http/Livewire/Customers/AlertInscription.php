<?php

namespace App\Http\Livewire\Customers;

use Livewire\Component;
use App\Models\Customer;

class AlertInscription extends Component
{
    public $customer_id;
    public $customer;
    public $listeners = ['getUserAgain' => '$refresh'];

    public function mount($customer_id)
    {
        $this->customer_id = $customer_id;
        $this->customer = $customer_id ? Customer::with('lastInscription')->findOrFail($customer_id) : null;
    }

    public function render()
    {
        return view('livewire.customers.alert-inscription');
    }
}
