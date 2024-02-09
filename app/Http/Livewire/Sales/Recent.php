<?php

declare(strict_types=1);

namespace App\Http\Livewire\Sales;

use App\Http\Livewire\WithSorting;
use App\Models\Sale;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Recent extends Component
{
    use WithPagination;
    use WithSorting;
    use WithFileUploads;
    use LivewireAlert;
    use Datatable;

    public $sale;

    /** @var array<string> */
    public $listeners = [
        'recentSales', 'showModal',
        'refreshIndex' => '$refresh',
    ];

    public $showModal = false;

    public $searchTerm = '';

    public $sortField = 'created_at';
    public $sortAsc = false;

    public $recentSales;

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
        $this->perPage = 10;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Sale())->orderable;
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
                ->orWhere('id', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('reference', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('total_amount', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('note', 'like', '%' . $this->searchTerm . '%');

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
        abort_if(Gate::denies('sale_access'), 403);

        $query = Sale::with('customer', 'saleDetails', 'saleDetailsService', 'saleDetailsTax')
            ->when($this->sortField, function ($que) {
                $que->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            })
        ;

       $this->applySearchFilter($query);

        $sales = $query->paginate($this->perPage);

        return view('livewire.sales.recent', compact('sales'));
    }

    public function showModal($id)
    {
        abort_if(Gate::denies('sale_access'), 403);

        $this->sale = Sale::with('saleDetails')->findOrFail($id);

        $this->showModal = true;
    }

    public function recentSales()
    {
        abort_if(Gate::denies('sale_access'), 403);

        $this->showModal = false;
        $this->recentSales = true;
    }
}
