<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use Asantibanez\LivewireSelect\LivewireSelect;
use Illuminate\Support\Collection;
use App\Models\Customer;

class SelectCustomerSecond extends LivewireSelect
{
    public function options($searchTerm = null): Collection
    {
        return Customer::select(['name', 'id'])
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where('name', 'like', "%$searchTerm%");
            })
            ->orderBy('name')
            ->take(20)
            ->get()
            ->map(function (Customer $customer) {
                return [
                    'value' => $customer->id,
                    'description' => $customer->name,
                ];
            });
    }

    public function selectedOption($value = null)
    {
        $select_customer = Customer::find($value);
        $this->emit('updatedCustomerIDSecond', $value);

        return [
            'title' => optional($select_customer)->name,
            'description' => optional($select_customer)->name
        ];
    }
}
