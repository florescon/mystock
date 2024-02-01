<?php

namespace App\Http\Livewire\Inscriptions;

use App\Http\Livewire\WithSorting;
use App\Models\SaleDetailsService;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Query\Builder;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use Datatable;

    /** @var array<string> */
    public $listeners = [
        'importModal',   'delete',
        'refreshIndex' => '$refresh',
    ];

    public $startDate;
    public $endDate;

    public $searchTerm = '';

    public $sortField = 'created_at';
    public $sortAsc = false;

    public $importModal = false;

    public $listsForFields = [];

    /** @var array<array<string>> */
    protected $queryString = [
        'searchTerm' => ['except' => ''],
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

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new SaleDetailsService())->orderable;
        $this->startDate = now()->subYear()->format('Y-m-d');
        $this->endDate = now()->endOfDay()->format('Y-m-d');
    }

    public function updatedStartDate($value)
    {
        $this->startDate = $value;
    }

    public function updatedEndDate($value)
    {
        $this->endDate = $value;
    }

    public function filterByType($type)
    {
        switch ($type) {
            case 'day':
                $this->startDate = now()->startOfDay()->format('Y-m-d');
                $this->endDate = now()->endOfDay()->format('Y-m-d');

                break;
            case 'month':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');

                break;
            case 'year':
                $this->startDate = now()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->endOfYear()->format('Y-m-d');

                break;
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    private function applySearchFilter($sales)
    {
        if ($this->searchTerm) {

            return $sales->where(function($queryy) {
                $queryy->whereHas('customer', function ($qu) {
                    $qu->whereRaw("name LIKE \"%$this->searchTerm%\"");
                })
                ->orWhere('name', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('sub_total', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('sale_id', 'like', '%' . $this->searchTerm . '%');

            });
        }

        return null;
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function render()
    {
        // abort_if(Gate::denies('sale_access'), 403);

        $query = SaleDetailsService::query()->with(['customer', 'service', 'sale'])
            ->whereHas('service', function($q) {
                $q->where('service_type', 0);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate.' 23:59:59'])
            ->when($this->sortField, function ($que) {
                $que->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            })
            ;

        $this->applySearchFilter($query);

        $inscriptions = $query->paginate($this->perPage);


        return view('livewire.inscriptions.index', compact('inscriptions'));
    }
}
