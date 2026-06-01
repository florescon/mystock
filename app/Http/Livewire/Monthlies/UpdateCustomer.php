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

class UpdateCustomer extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $editCustomer = false;

    /** @var mixed */
    public $monthlie;

    public $selectedDays = [];

    public $hour;
    public $hourSelected;

    public $quantitySelectDays = [];

    public $customer_id;

    /** @var array<string> */
    public $listeners = [
        'editCustomer',
        'updatedCustomerIDD',
    ];


    public $mix;


    public function updatedCustomerId()
    {
        $this->emit('getUserAgain', $this->customer_id);
    }

    public function updatedCustomerIDD(?int $id = null)
    {
        $this->customer_id = $id;
        $this->emit('getUserAgain', $this->customer_id);
    }

    public function editCustomer($id): void
    {   
        $this->customer_id = null;

        $this->resetErrorBag();

        $this->resetValidation();

        $this->monthlie = SaleDetailsService::where('id', $id)->firstOrFail();

        $this->editCustomer = true;
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

    // public function update()
    // {
    //     $validatedData = $this->validate();

    //     try {
    //         if ($this->image) {
    //             $date = date("Y-m-d");
    //             $imageName = $this->image->store("services/".$date,'public');
    //             $this->service->update(['image' => $imageName]);
    //         }

    //         $this->service->save($validatedData);

    //         $this->emit('refreshIndex');
    //         $this->image = null;

    //         $this->alert('success', __('Service updated successfully.'));

    //         $this->editCustomer = false;
    //     } catch (Throwable $th) {
    //         $this->alert('error', __('Error.').$th->getMessage());
    //     }
    // }

    public function getDaysProperty()
    {
        return Days::cases();
    }

    public function updateCustomerMonthlie()
    {
        $this->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
        ]);

        $this->monthlie->update([
            'customer_id' => $this->customer_id,
        ]);

        $this->alert('success', 'Cliente actualizado a la mensualidad');

        $this->emit('refreshIndex');
        // $this->resetSchedule();

        $this->editCustomer = false;
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
        return view('livewire.monthlies.update-customer');
    }
}
