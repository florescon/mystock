<?php

declare(strict_types=1);

namespace App\Http\Livewire\Services;

use App\Models\Customer;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Models\Service;
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
    public $selectedDays_ = [];

    public $quantity;

    public $quantity_;

    /** @var array<string> */
    public $listeners = ['createAssociate', 'showCustomerAssociate'];

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
    }

    public function selectService($service)
    {
        // dd(implode(',', $this->selectedDays));

        $this->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $this->emit('serviceSelected', [$service, null, $this->quantity, $this->selectedDays]);

        $this->showCustomerAssociate = false;
    }

    public function selectServiceWithCustomer($service)
    {
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

        if($this->customerAssociate){
            $this->emit('serviceSelected', [$service, $this->customerAssociate, $this->quantity_, $this->selectedDays_]);
        }

        $this->showCustomerAssociate = false;
    }

    public function showCustomerAssociate($id)
    {
        abort_if(Gate::denies('sale_access'), 403);

        $this->serviceAssociate = Service::findOrFail($id);

        $this->showCustomerAssociate = true;
    }

    public function updatedCustomerAssociate()
    {
        $this->emit('getUserAgain', $this->customerAssociate);
    }

    public function getCustomersProperty()
    {
        return Customer::select(['name', 'id'])->get();
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

        $services = $query->paginate($this->perPage);

        return view('livewire.services.associate', compact('services'));
    }
}
