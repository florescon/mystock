<?php

namespace App\Http\Livewire\CashHistory;

use App\Http\Livewire\WithSorting;
use App\Models\Sale;
use App\Models\Cash;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithFileUploads;
    use LivewireAlert;
    use Datatable;

    /** @var Cash|null */
    public $cash;

    /** @var array<string> */
    public $listeners = [
        'refreshIndex' => '$refresh',
    ];

    public $startDate;
    public $endDate;

    public $importModal = false;


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

    /** @var array */
    protected $rules = [
        'customer_id'         => 'required|numeric',
        'reference'           => 'required|string|max:255',
        'tax_percentage'      => 'required|string|min:0|max:100',
        'discount_percentage' => 'required|string|min:0|max:100',
        'shipping_amount'     => 'required|numeric',
        'total_amount'        => 'required|numeric',
        'paid_amount'         => 'required|numeric',
        'status'              => 'required|integer|min:0|max:100',
        'payment_method'      => 'required|integer|min:0|max:100',
        'note'                => 'string|nullable|max:1000',
    ];

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Cash())->orderable;
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

        $query = Cash::with(['sales', 'sale_payments', 'expenses', 'user'])
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ])
            ->whereBetween('created_at', [$this->startDate, $this->endDate.' 23:59:59'])
            ;

        $cashes = $query->paginate($this->perPage);

        return view('livewire.cash-history.index', compact('cashes'));
    }

    public function delete(Cash $cash)
    {
        abort_if(Gate::denies('cash_delete'), 403);

        $cash->delete();

        $this->emit('refreshIndex');

        $this->alert('success', __('Cash Out deleted successfully.'));
    }

    public function downloadSample()
    {
        return Storage::disk('exports')->download('cash_import_sample.xls');
    }

}
