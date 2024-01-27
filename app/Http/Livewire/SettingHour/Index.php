<?php

namespace App\Http\Livewire\SettingHour;

use App\Http\Livewire\WithSorting;
use App\Models\SettingHour;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use Datatable;

    /** @var mixed */
    public $hour;

    /** @var array<string> */
    public $listeners = [
        'showModal',
        'delete',
        'refreshIndex' => '$refresh',
    ];

    public $showModal = false;

    public $editModal = false;

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
        $this->orderable = (new SettingHour())->orderable;
    }

    public function render()
    {
        // abort_if(Gate::denies('currency_access'), 403);

        $query = SettingHour::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $hours = $query->paginate($this->perPage);

        return view('livewire.setting-hour.index', compact('hours'));
    }

    public function showModal(SettingHour $hour): void
    {
        // abort_if(Gate::denies('currency_show'), 403);

        $this->hour = SettingHour::find($hour->id);

        $this->showModal = true;
    }

    public function delete(SettingHour $hour): void
    {
        // abort_if(Gate::denies('currency_delete'), 403);

        $hour->delete();

        $this->alert('success', __('SettingHour deleted successfully!'));
    }
}
