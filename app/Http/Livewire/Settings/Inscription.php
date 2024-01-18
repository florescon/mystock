<?php

namespace App\Http\Livewire\Settings;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Setting;

class Inscription extends Component
{
    use LivewireAlert;

    public $inscription;
    public $settings;

    /** @var array */
    protected $rules = [
        'settings.inscription'             => 'required',
    ];

    public function mount()
    {
        $settings = Setting::firstOrFail();
        $this->settings = $settings;
        $this->inscription = $settings->inscription;
    }
    public function update()
    {
        $this->settings->inscription = $this->inscription;
        $this->settings->save();

        cache()->forget('settings');

        $this->alert('success', __('Settings Updated successfully !'));
    }

    public function render()
    {
        return view('livewire.settings.inscription');
    }
}
