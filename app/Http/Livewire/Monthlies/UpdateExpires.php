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
use Carbon\Carbon;

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

        $days = Carbon::now()->diffInDays(Carbon::parse($this->hour), false);

        for ($i = 1; $i <= 6; $i++) {
            $this->quantitySelectDays[$i] = $days;
        }

        $this->desactivateMix();

    }

    public function getDaysProperty()
    {
        return Days::cases();
    }


    public function selectService()
    {

        $newDate = Carbon::parse($this->hour);

        // Fecha mínima permitida
        $minDate = Carbon::parse($this->monthlie->created_at)
            ->addDays(30);

        // Validación
        if ($newDate->lt($minDate)) {

            $this->alert(
                'error',
                'La fecha debe ser mayor o igual a ' . $minDate->format('d-m-Y')
            );

            return;
        }

        $this->monthlie->update([
            'expires_at' => $newDate,
        ]);

        $this->alert('success', 'Fecha de expiración actualizada');

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
