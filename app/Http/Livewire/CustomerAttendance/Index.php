<?php

declare(strict_types=1);

namespace App\Http\Livewire\CustomerAttendance;

use App\Exports\CustomerExport;
use App\Http\Livewire\WithSorting;
use App\Models\Customer;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use WithFileUploads;
    use Datatable;

    public $customer;
    public $selectedCustomer;

    public $file;

    public $listeners = [
        'refreshIndex' => '$refresh',
        'showModal',
        'exportAll', 'downloadAll',
        'delete',
    ];

    public $showModal = false;


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
        $this->orderable = (new Customer())->orderable;
    }

    public function delete(Customer $customer)
    {
        abort_if(Gate::denies('customer_delete'), 403);

        $customer->delete();

        $this->alert('warning', __('Customer deleted successfully'));
    }

    public function showModal($id): void
    {
        abort_if(Gate::denies('customer_access'), 403);

        $this->selectedCustomer = Customer::find($id);

        $this->showModal = true;
    }

    public function downloadAll(Customer $customers): BinaryFileResponse|Response
    {
        abort_if(Gate::denies('customer_access'), 403);

        return (new CustomerExport($customers))->download('clientes.xls', \Maatwebsite\Excel\Excel::XLS);
    }


    public function exportAll(): BinaryFileResponse|Response
    {
        abort_if(Gate::denies('customer_access'), 403);

        return $this->callExport()->download('clientes.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    private function callExport(): CustomerExport
    {
        return new CustomerExport();
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('customer_access'), 403);

        $query = Customer::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ])
        ->withSum('saleDetailsServices as total_attendances', 'available_attendances')
        ->withMax('saleDetailsServices as last_attendance', 'created_at')
        ->addSelect([
            'last_time_day' => Attendance::select('created_at')
                ->whereColumn('customer_id', 'customers.id')
                ->latest('id') // o 'time_day' si ese define el orden real
                ->limit(1)
        ]);

        $customers = $query->paginate($this->perPage);

        return view('livewire.customer-attendance.index', compact('customers'));
    }

}
