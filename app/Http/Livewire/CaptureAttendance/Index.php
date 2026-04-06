<?php

namespace App\Http\Livewire\CaptureAttendance;

use App\Http\Livewire\WithSorting;
use App\Models\SettingHour;
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

    public function render()
    {
        $hours = SettingHour::get();
        $hours = $hours->sortBy([ ['is_am', 'desc'], ['hour', 'asc'] ]);

        return view('livewire.capture-attendance.index', compact('hours'));
    }
}
