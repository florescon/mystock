<?php

namespace App\Http\Livewire\SettingHour;

use App\Models\SettingHour;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Edit extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['editModal'];

    public $editModal = false;

    /** @var mixed */
    public $hour;

    /** @var array */
    protected $rules = [
        'hour.hour'          => 'required|integer|between:1,12|min:2|max:50',
        'hour.is_am'          => 'required|boolean',
    ];

    protected $messages = [
        'hour.hour.required'          => 'The hour field cannot be empty.',
        'hour.is_am.required'          => 'The is am field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        abort_if(Gate::denies('hour_update'), 403);

        return view('livewire.setting-hour.edit');
    }

    public function editModal($id): void
    {
        abort_if(Gate::denies('hour_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->hour = SettingHour::where('id', $id)->firstOrFail();

        $this->editModal = true;
    }

    public function update(): void
    {
        try {
            $validatedData = $this->validate();

            $this->hour->save($validatedData);

            $this->alert('success', __('Hour updated successfully.'));

            $this->emit('refreshIndex');

            $this->editModal = false;
        } catch (Throwable $th) {
            $this->alert('error', __('Error.').$th->getMessage());
        }
    }

}
