<?php

namespace App\Http\Livewire\CaptureAttendanceHour;

use Livewire\Component;
use App\Models\SettingHour;
use App\Models\SaleDetailsService;
use App\Enums\Days;
use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $settinghour;

    public $filterType = 'horario'; // valor por defecto

    public $search = '';

    public function mount(SettingHour $settinghour)
    {
        $this->settinghour = SettingHour::findOrFail($settinghour->id);
    }

    public function toggleAttendance($serviceId)
    {
        $period = $this->settinghour->is_am ? 'AM' : 'PM';
        $day = strtoupper(\Carbon\Carbon::now()->locale('es')->dayName);
        $dayTime = $day . ' — ' . $this->settinghour->hour . ' ' . $period;

        $serv = SaleDetailsService::findOrFail($serviceId);

        $attendance = Attendance::where('sale_details_service_id', $serv->id)
            ->where('time_day', $dayTime)
            ->whereDate('created_at', now())
            ->first();

        if (!$attendance) {
            // Opcional: proteger de negativos
            if ($serv->available_attendances > 0) {
                $serv->decrement('available_attendances', 1);

                Attendance::create([
                    'sale_details_service_id' => $serv->id,
                    'customer_id' => $serv->customer_id,
                    'user_id' => auth()->id(),
                    'time_day' => $dayTime,
                ]);
            }
        }
    }

    public function deleteAttendance($serviceId)
    {
        $period = $this->settinghour->is_am ? 'AM' : 'PM';
        $day = strtoupper(\Carbon\Carbon::now()->locale('es')->dayName);
        $dayTime = $day . ' — ' . $this->settinghour->hour . ' ' . $period;

        $serv = SaleDetailsService::findOrFail($serviceId);

        $attendance = Attendance::where([
            'sale_details_service_id' => $serv->id,
            'time_day' => $dayTime,
        ])->first();

        if($attendance){
            $serv->increment('available_attendances', 1);
            $attendance->delete();
        }
    }

    public function render()
    {
        // Convertir AM/PM
        $period = $this->settinghour->is_am ? 'AM' : 'PM';

        // Aquí puedes definir el día dinámicamente si quieres
        $day = strtoupper(Carbon::now()->locale('es')->dayName);

        // Construir el string dinámico
        $dayTime = $day . ' — ' . $this->settinghour->hour . ' ' . $period;

        // Obtenemos todos los registros que tienen este día/hora en 'with_days'
        // $servicesToUpdate = SaleDetailsService::with('customer')->where('with_days', 'like', "%$dayTime%")->where('available_attendances', '>', 0)->get();

        // $query = SaleDetailsService::query()
        //     ->join('customers', 'customers.id', '=', 'sale_details_services.customer_id')
        //     ->where('sale_details_services.available_attendances', '>', 0)
        //     ->orderBy('customers.name', 'asc')
        //     ->select('sale_details_services.*')
        //     ->with('customer');

        $query = SaleDetailsService::query()
            ->join('customers', 'customers.id', '=', 'sale_details_services.customer_id')
            ->orderBy('customers.name', 'asc')
            ->select('sale_details_services.*')
            ->with('customer')
            ->whereBetween('sale_details_services.created_at', [
                now()->subDays(45)->startOfDay(),
                now()->endOfDay()
            ])
            ->where(function ($q) use ($dayTime) {

                $q->where('sale_details_services.available_attendances', '>', 0)

                  ->orWhereExists(function ($sub) use ($dayTime) {
                      $sub->select(\DB::raw(1))
                          ->from('attendances')
                          ->whereColumn('attendances.sale_details_service_id', 'sale_details_services.id')
                          ->where('attendances.time_day', $dayTime)
                          ->whereDate('attendances.created_at', now());
                  });

            });

        if ($this->filterType === 'horario') {
            $query->where('sale_details_services.with_days', 'like', "%$dayTime%");
        }
        elseif($this->filterType === 'buscar'){

            $query->where(function ($q) {
                $q->where('customers.name', 'like', '%' . $this->search . '%')
                  ->orWhere('sale_details_services.with_days', 'like', '%' . $this->search . '%');
            });    
        }
        else{
            $query->where('sale_details_services.with_days', '>> HORARIO MIXTO');
        }

        $attendances = \App\Models\Attendance::where('time_day', $dayTime)
            ->whereDate('created_at', Carbon::today())
            ->pluck('sale_details_service_id')
            ->toArray();

        $servicesToUpdate = $query->get();

        return view('livewire.capture-attendance-hour.index', compact('servicesToUpdate', 'attendances'));
    }
}
