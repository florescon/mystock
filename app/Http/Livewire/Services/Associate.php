<?php

declare(strict_types=1);

namespace App\Http\Livewire\Services;

use App\Models\Customer;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Models\Service;
use App\Models\SettingHour;
use Livewire\WithPagination;
use App\Http\Livewire\WithSorting;
use App\Traits\Datatable;
use App\Enums\Days;

class Associate extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use Datatable;

    public $customerAssociate;

    public $serviceAssociate;
    public $selectedDays = [];
    public $selectedDaysSecond = [];

    public $hourFirst;

    public $hourSelected;

    public $quantity;

    public $quantitySelectDays = [];

    public $quantitySelectDaysSecond = [];

    public $quantity_;

    public $mix;
    public $mixSecond;

    /** @var array<string> */
    public $listeners = [
        'createAssociate', 
        'showCustomerAssociate', 
        'updatedCustomerIDSecond',
    ];

    public $showCustomerAssociate = false;

    /** @var array<array<string>> */
    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

    public $createAssociate = false;

    public function createAssociate(): void
    {
        $this->createAssociate = true;
    }

    public function mount(): void
    {
        $this->quantity = 1;
        $this->quantity_ = 1;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Service())->orderable;
    }

    public function updatedShowCustomerAssociate()
    {
        $this->quantity = 1;
        $this->quantity_ = 1;

        $this->selectedDays = [];
        $this->selectedDaysSecond = [];

        $this->quantitySelectDays = [];
        $this->quantitySelectDaysSecond = [];

        $this->hourFirst = null;
        $this->hourSelected = null;
    }

    public function UpdFirstColumn()
    {
        $this->quantity = 1;
        $this->selectedDays = [];
        $this->quantitySelectDays = [];
        $this->hourFirst = null;
    }

    public function UpdSecondColumn()
    {
        $this->quantity_ = 1;
        $this->selectedDaysSecond = [];
        $this->quantitySelectDaysSecond = [];
        $this->hourSelected = null;
    }


    public function updatedMix()
    {
        if($this->mix === true){
            $this->UpdFirstColumn();   
        }    
    }

    public function updatedMixSecond()
    {
        if($this->mixSecond === true){
            $this->UpdSecondColumn();   
        }    
    }

    public function desactivateMix()
    {
        $this->reset('mix');
    }

    public function desactivateMixSecond()
    {
        $this->reset('mixSecond');
    }

    public function updatedCustomerIDSecond(?int $id = null)
    {
        $this->customerAssociate = $id;
        $this->emit('getUserAgainSecond', $this->customerAssociate);
    }

    public function updatedSelectedDays()
    {
        $this->reset('mix');
    }

    public function updatedSelectedDaysSecond()
    {
        $this->reset('mixSecond');
    }

    public function selectService($service)
    {
        $getSelectedDays = array();
        $getSelectedHours = array();

        foreach($this->selectedDays as $key => $day ){  
            if($day !== false){
                $hour = array_key_exists($key, $this->quantitySelectDays) ? $this->quantitySelectDays[$key] : '';
                $getSelectedDays[$key] = $day ? $day . ($hour ? ' — '.$hour : '') : false;
                $getSelectedHours[$key] = $hour;
            }
        }

        $this->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);
        
        // dd(count(array_unique($getSelectedHours)));
        $getHour = null;
        if($this->quantitySelectDays){
            foreach($this->quantitySelectDays as $day){
                $getHour = $day;
            }
        }

        if($this->mix){
            $selectedDays = $service['with_days'] ? ['>> HORARIO MIXTO'] : [];
            $hour         = $service['with_days'] ? 'IND.' : null;
        }
        else{
            $selectedDays = $service['with_days'] ? ( $getSelectedDays ? $getSelectedDays : []) : [];
            $hour = $service['with_days'] ? ( (count(array_unique($getSelectedHours)) > 1) ? 'VARIAS' : ($this->hourFirst ?? $getHour) ) : null;
        }

        // dd($hour);

        $quantity = $service['with_input'] ? $this->quantity : 1; 

        $this->emit('serviceSelected', [$service, null, $quantity, $selectedDays, $hour]);

        $this->showCustomerAssociate = false;
    }

    public function selectServiceWithCustomer($service)
    {
        $getSelectedDaysSecond = array();
        $getSelectedHoursSecond = array();

        foreach($this->selectedDaysSecond as $key => $day ){  
            if($day !== false){
                $hour = array_key_exists($key, $this->quantitySelectDaysSecond) ? $this->quantitySelectDaysSecond[$key] : '';
                $getSelectedDaysSecond[$key] = $day ? $day . ($hour ? ' — '.$hour : '') : false;
                $getSelectedHoursSecond[$key] = $hour;
            }
        }

        $this->validate([
            'customerAssociate' => [
                'required',
                'integer',
            ],
            'quantity_' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $getHour = null;
        if($this->quantitySelectDaysSecond){
            foreach($this->quantitySelectDaysSecond as $day){
                $getHour = $day;
            }
        }

        if($this->mixSecond){
            $selectedDaysSecond        = $service['with_days'] ? ['>> HORARIO MIXTO'] : [];
            $hourSelected         = $service['with_days'] ? 'IND.' : null;
        }
        else{
            $selectedDaysSecond = $service['with_days'] ? ( $getSelectedDaysSecond ? $getSelectedDaysSecond : []) : [];
            // $selectedDaysSecond = $service['with_days'] ? $this->selectedDaysSecond : [];

            $hourSelected = $service['with_days'] ? ( (count(array_unique($getSelectedHoursSecond)) > 1) ? 'VARIAS' : ($this->hourSelected ?? $getHour) ) : null;
            // $hourSelected = $service['with_days'] ? $this->hourSelected : null;
        }

        $quantity_ = $service['with_input'] ? $this->quantity_ : 1; 

        if($this->customerAssociate){
            $this->emit('serviceSelected', [$service, $this->customerAssociate, $quantity_, $selectedDaysSecond, $hourSelected]);
        }

        $this->showCustomerAssociate = false;
    }

    public function showCustomerAssociate($id)
    {
        abort_if(Gate::denies('expense_access'), 403);

        $this->serviceAssociate = Service::findOrFail($id);

        $this->showCustomerAssociate = true;
    }

    public function setHourFirst()
    {
        $this->updatedHourFirst();
        $this->desactivateMix();
    }

    public function updatedHourFirst()
    {
        for ($i = 1; $i <= 6; $i++){
            $this->quantitySelectDays[$i] = $this->hourFirst;
        }
        $this->desactivateMix();
    }

    public function setHourSecond()
    {
        $this->updatedHourSelected();
        $this->desactivateMixSecond();
    }

    public function updatedHourSelected()
    {
        for ($i = 1; $i <= 6; $i++){
            $this->quantitySelectDaysSecond[$i] = $this->hourSelected;
        }
        $this->desactivateMixSecond();
    }

    public function updatedCustomerAssociate()
    {
        $this->emit('getUserAgainSecond', $this->customerAssociate);
    }

    public function getCustomersProperty()
    {
        return Customer::select(['name', 'id'])->orderBy('name')->get();
    }

    public function getDaysProperty()
    {
        return Days::cases();
    }

    public function cancel()
    {
        $this->showCustomerAssociate = false;
        $this->updatedShowCustomerAssociate();
    }

    public function render()
    {
        $query = Service::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $hours = SettingHour::get();

        $hours = $hours->sortBy([ ['is_am', 'desc'], ['hour', 'asc'] ]);

        $services = $query->paginate($this->perPage);

        return view('livewire.services.associate', compact('services', 'hours'));
    }
}
