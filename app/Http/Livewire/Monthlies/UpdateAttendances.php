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

class UpdateAttendances extends Component
{

    use LivewireAlert;
    use WithFileUploads;

    public $editAttendances = false;

    /** @var mixed */
    public $monthlie;

    public $selectedDays = [];

    public $hour;
    public $hourSelected;

    public $quantitySelectDays = [];

    /** @var array<string> */
    public $listeners = ['editAttendances'];

    public $mix;

    public function editAttendances($id): void
    {
        $this->resetSchedule();
        $this->resetErrorBag();

        $this->resetValidation();

        $this->monthlie = SaleDetailsService::where('id', $id)->firstOrFail();

        $this->editAttendances = true;
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
        $hour = (int) $this->hour;

        // Validar que esté entre 1 y 100
        if ($hour < 1 || $hour > 100) {
            $this->alert('error', 'El valor debe estar entre 1 y 100');
            return;
        }
        $this->monthlie->increment('available_attendances', $hour);

        $this->alert('success','Fecha de expiración actualizada');

        $this->emit('refreshIndex');
        // $this->resetSchedule();

        $this->editAttendances = false;
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
        return view('livewire.monthlies.update-attendances');
    }
}
