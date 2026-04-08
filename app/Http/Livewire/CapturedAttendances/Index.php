<?php

namespace App\Http\Livewire\CapturedAttendances;

use App\Http\Livewire\WithSorting;
use App\Models\SaleDetailsService;
use App\Models\Attendance;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

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

    public $variable = 'docs';

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
        $this->startDate = Carbon::now()
            ->subMonth()   // retrocede un mes
            ->subDays(10)   // retrocede 10 días más
            ->format('Y-m-d');

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
                ->orWhere('time_day', 'like', '%' . $this->searchTerm . '%');

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

        $query = Attendance::with(['customer', 'user', 'sale_details_service'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate.' 23:59:59'])
            ->when($this->sortField, function ($que) {
                $que->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            })
            ;

        $this->applySearchFilter($query);

        $inscriptions = $query->paginate($this->perPage);


        return view('livewire.captured-attendances.index', compact('inscriptions'));

    }
}
