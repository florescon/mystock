<?php

namespace App\Http\Livewire\Monthlies;

use App\Models\SaleDetailsService;
use App\Models\SettingHour;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;
use App\Enums\Days;

class UpdateExpires extends Component
{

    use LivewireAlert;
    use WithFileUploads;

    public $editExpires = false;

    /** @var mixed */
    public $monthlie;

    public $selectedDays = [];

    public $hour;
    public $hourSelected;

    public $quantitySelectDays = [];

    /** @var array<string> */
    public $listeners = ['editExpires'];

    public $mix;

    public function editExpires($id): void
    {
        $this->resetSchedule();
        $this->resetErrorBag();

        $this->resetValidation();

        $this->monthlie = SaleDetailsService::where('id', $id)->firstOrFail();

        $this->editExpires = true;
    }

    public function setHour()
    {
        $this->updatedHour();
        $this->desactivateMix();
    }

    public function updatedHour()
    {
        for ($i = 1; $i <= 6; $i++){
            $this->quantitySelectDays[$i] = $this->hour;
        }

        $this->desactivateMix();
    }

    public function getDaysProperty()
    {
        return Days::cases();
    }

    public function selectService()
    {
        $this->monthlie->update([
            'expires_at' => $this->monthlie->expires_at->addDays($this->hour),
        ]);

        $this->alert('success','Fecha de expiración actualizada');

        $this->emit('refreshIndex');
        // $this->resetSchedule();

        $this->editExpires = false;
    }

    public function resetSchedule()
    {
        $this->selectedDays = [];
        $this->quantitySelectDays = [];
        $this->hour = null;
    }

    public function updatedSelectedDays()
    {
        $this->reset('mix');
    }

    public function updatedMix()
    {
        if($this->mix === true){
            $this->resetSchedule();   
        }    
    }

    public function desactivateMix()
    {
        $this->reset('mix');
    }

    public function render()
    {
        return view('livewire.monthlies.update-expires');
    }
}
