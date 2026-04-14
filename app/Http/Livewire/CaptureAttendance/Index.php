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
        $hours = SettingHour::get()->sortBy(function ($item) {
            if ($item->hour == 12) {
                return $item->is_am ? 0 : 12;
            }

            return $item->hour + ($item->is_am ? 0 : 12);
        });

        return view('livewire.capture-attendance.index', compact('hours'));
    }
}
