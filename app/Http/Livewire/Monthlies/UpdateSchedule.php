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

class UpdateSchedule extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $editModal = false;

    /** @var mixed */
    public $monthlie;

    public $selectedDays = [];

    public $hour;
    public $hourSelected;

    public $quantitySelectDays = [];

    /** @var array<string> */
    public $listeners = ['editModal'];

    public $mix;

    // /** @var array */
    // protected $rules = [
    //     'service.name'        => 'required|string||min:3|max:50',
    //     'service.note' => 'nullable|string',
    //     'service.price' => 'required|integer|min:1',
    //     'service.service_type' => 'required',
    //     'image' => 'nullable|mimes:jpeg,png,jpg,gif|size:2024',
    // ];

    // protected $messages = [
    //     'service.name.required' => 'The name field cannot be empty.',
    // ];

    // public function updated($propertyName): void
    // {
    //     $this->validateOnly($propertyName);
    // }

    public function editModal($id): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->monthlie = SaleDetailsService::where('id', $id)->firstOrFail();

        $this->editModal = true;
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

    //         $this->editModal = false;
    //     } catch (Throwable $th) {
    //         $this->alert('error', __('Error.').$th->getMessage());
    //     }
    // }

    public function getDaysProperty()
    {
        return Days::cases();
    }

    public function selectService()
    {

        if(!$this->mix){
            $this->validate([
                'selectedDays' => [
                    'required',
                ],
            ]);
        }

        $getSelectedDays = array();
        $getSelectedHours = array();

        foreach($this->selectedDays as $key => $day ){  
            if($day !== false){
                $hour = array_key_exists($key, $this->quantitySelectDays) ? $this->quantitySelectDays[$key] : '';
                $getSelectedDays[$key] = $day ? $day . ($hour ? ' — '.$hour : '') : false;
                $getSelectedHours[$key] = $hour;
            }
        }

        // dd(count(array_unique($getSelectedHours)));
        $getHour = null;
        if($this->quantitySelectDays){
            foreach($this->quantitySelectDays as $day){
                $getHour = $day;
            }
        }

        if($this->mix){
            $selectedDays = ['>> HORARIO MIXTO'];
            $hour         = 'IND.';
        }
        else{
            $selectedDays = $getSelectedDays ? $getSelectedDays : [];
            $hour = (count(array_unique($getSelectedHours)) > 1) ? 'VARIAS' : ($this->hour ?? $getHour) ;
        }

        $this->monthlie->update([
            'with_days' => $selectedDays,
            'hour'      => $hour,
        ]);

        $this->alert('success', __('Schedule updated.'));

        $this->emit('refreshIndex');
        // $this->resetSchedule();

        $this->editModal = false;
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
        $hours = SettingHour::get();

        $hours = $hours->sortBy([ ['is_am', 'desc'], ['hour', 'asc'] ]);

        return view('livewire.monthlies.update-schedule', compact('hours'));
    }
}
