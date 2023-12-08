<?php

declare(strict_types=1);

namespace App\Http\Livewire\Services;

use App\Http\Livewire\WithSorting;
use App\Imports\ServicesImport;
use App\Models\Service;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Response;

class Index extends Component
{
    use WithPagination;
    use LivewireAlert;
    use WithFileUploads;
    use WithSorting;
    use Datatable;

    /** @var mixed */
    public $service;

    public $serviceIds;

    /** @var array<string> */
    public $listeners = [
        'refreshIndex' => '$refresh',
        'showModal', 'importModal',
        'delete',
    ];

    public $image;

    public $file;

    /** @var bool */
    public $showModal = false;

    /** @var bool */
    public $deleteModal = false;

    /** @var bool */
    public $importModal = false;

    public $selectAll;

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

    public function selectAll()
    {
        if (count(array_intersect($this->selected, Service::pluck('id')->toArray())) === count(Service::pluck('id')->toArray())) {
            $this->selected = [];
        } else {
            $this->selected = Service::pluck('id')->toArray();
        }
    }

    public function selectPage()
    {
        if (count($this->selected, Service::paginate($this->perPage)->pluck('id')->toArray())) {
            $this->selected = [];
        } else {
            $this->selected = $this->serviceIds;
        }
    }

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Service())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('service_access'), 403);

        $query = Service::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $services = $query->paginate($this->perPage);

        return view('livewire.services.index', compact('services'));
    }

    public function showModal(Service $service): void
    {
        abort_if(Gate::denies('service_show'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->service = Service::find($service->id);

        $this->showModal = true;
    }

    public function confirmed()
    {
        $this->emit('delete');
    }

    public function deleteModal($service)
    {
        $this->confirm(__('Are you sure you want to delete this?'), [
            'toast'             => false,
            'position'          => 'center',
            'showConfirmButton' => true,
            'cancelButtonText'  => __('Cancel'),
            'onConfirmed'       => 'delete',
        ]);
        $this->service = $service;
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('service_delete'), 403);

        Service::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Service $service): void
    {
        abort_if(Gate::denies('service_delete'), 403);

        $service->delete();

        $this->alert('success', __('Service deleted successfully.'));
    }

    public function importModal(): void
    {
        abort_if(Gate::denies('service_import'), 403);

        $this->importModal = true;
    }

    public function downloadSample()
    {
        $download = public_path('files/marcas-muestra.xlsx');
        return Response::download($download); 
    }

    public function import(): void
    {
        abort_if(Gate::denies('service_import'), 403);

        $this->validate([
            'file' => [
                'required',
                File::types(['xlsx', 'xls'])
                    ->max(1024),
            ],
        ]);

        Excel::import(new ServicesImport(), $this->file);

        $this->alert('success', __('Services imported successfully.'));

        $this->import = false;
        $this->importModal = false;
    }
}
