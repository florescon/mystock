<?php

namespace App\Http\Livewire\Monthlies;

use Livewire\Component;

class Index extends Component
{
    public $variable = 'docs';

    public function render()
    {
        return view('livewire.monthlies.index');
    }
}
