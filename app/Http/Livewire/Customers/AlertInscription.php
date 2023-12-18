<?php

namespace App\Http\Livewire\Customers;

use Livewire\Component;
use App\Models\Customer;

class AlertInscription extends Component
{
    public $customer_id;
    public $customer;
    public $listeners = ['getUserAgain'];

    public function getUserAgain($customer_id){
        $this->customer_id = $customer_id;
    }

    public function render()
    {
        $this->customer = Customer::with('lastInscription')->findOrFail($this->customer_id);
        return view('livewire.customers.alert-inscription');
    }
}
