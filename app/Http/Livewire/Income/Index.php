<?php

declare(strict_types=1);

namespace App\Http\Livewire\Income;

use App\Exports\IncomeExport;
use App\Http\Livewire\WithSorting;
use App\Models\Expense;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use Datatable;

    /** @var mixed */
    public $income;

    /** @var array<string> */
    public $listeners = [
        'refreshIndex' => '$refresh',
        'showModal',
        'exportAll', 'downloadAll',
        'delete',
    ];

    public $showModal = false;
    public $showFilters = false;

    public $startDate;
    public $endDate;
    public $filterType;

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

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Expense())->orderable;
        $this->startDate = now()->startOfYear()->format('Y-m-d');
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

    public function render()
    {
        abort_if(Gate::denies('expense_access'), 403);

        $query = Expense::with(['category', 'user', 'warehouse'])
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ])
            ->whereBetween('created_at', [$this->startDate, $this->endDate.' 23:59:59'])
            ->incomes();

        $incomes = $query->paginate($this->perPage);

        return view('livewire.income.index', compact('incomes'));
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('expense_access'), 403);

        Expense::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Expense $income): void
    {
        abort_if(Gate::denies('expense_access'), 403);

        $income->delete();
    }

    public function showModal($id): void
    {
        abort_if(Gate::denies('expense_access'), 403);

        $this->income = Expense::find($id);

        $this->showModal = true;
    }

    public function downloadSelected(): BinaryFileResponse
    {
        abort_if(Gate::denies('expense_access'), 403);

        return $this->callExport()->forModels($this->selected)->download('incomes.xlsx');
    }

    public function downloadAll(): BinaryFileResponse
    {
        abort_if(Gate::denies('expense_access'), 403);

        return $this->callExport()->download('incomes.xlsx');
    }

    public function exportSelected(): BinaryFileResponse
    {
        abort_if(Gate::denies('expense_access'), 403);

        return $this->callExport()->forModels($this->selected)->download('incomes.pdf');
    }

    public function exportAll(): BinaryFileResponse
    {
        abort_if(Gate::denies('expense_access'), 403);

        return $this->callExport()->download('incomes.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    private function callExport(): IncomeExport
    {
        return new IncomeExport();
    }

}
