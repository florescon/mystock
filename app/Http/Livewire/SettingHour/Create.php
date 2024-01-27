<?php

namespace App\Http\Livewire\SettingHour;

use App\Models\SettingHour;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['createModal'];

    public $createModal = false;

    /** @var mixed */
    public $hour;

    /** @var array */
    protected $rules = [
        'hour.hour'          => 'required|integer|between:1,12|min:2|max:50',
        'hour.is_am'          => 'required|boolean',
    ];

    protected $messages = [
        'hour.hour.required'          => 'The hour field cannot be empty.',
        'hour.is_am.required'          => 'The is_am field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        // abort_if(Gate::denies('currency_create'), 403);

        return view('livewire.setting-hour.create');
    }

    public function createModal(): void
    {
        // abort_if(Gate::denies('currency_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->hour = new SettingHour();

        $this->createModal = true;
    }

    public function create(): void
    {
        $validatedData = $this->validate();

        try {
            $this->hour->save($validatedData);

            $this->alert('success', __('Hour created successfully.'));

            $this->emit('refreshIndex');

            $this->createModal = false;
        } catch (Throwable $th) {
            $this->alert('error', __('Error.').$th->getMessage());
        }
    }

}
