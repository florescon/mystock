<?php

declare(strict_types=1);

namespace App\Http\Livewire\Services;

use App\Models\Service;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class Edit extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $editModal = false;

    /** @var mixed */
    public $service = [
        'service_type' => null,
        'no_attendances' => null,
    ];


    public $image;

    /** @var array<string> */
    public $listeners = ['editModal'];

    /** @var array */
    protected $rules = [
        'service.name'        => 'required|string||min:3|max:50',
        'service.note' => 'nullable|string',
        'service.price' => 'required|integer|min:1',
        'service.service_type' => 'required',
        'service.no_attendances' => [
            'required_if:service.service_type,' . \App\Enums\ServiceType::MONTHLYPAYMENT->value,
            'integer',
            'min:1',
            'max:100',
        ],
        'image' => 'nullable|mimes:jpeg,png,jpg,gif|size:2024',
    ];

    protected $messages = [
        'service.name.required' => 'The name field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function editModal($id): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->service = Service::where('id', $id)->firstOrFail();

        $this->editModal = true;
    }

    public function update()
    {
        $validatedData = $this->validate();

        try {

            switch ($this->service->service_type->value) {
                case 1:
                    $this->service->with_days = 1;
                    $this->service->with_input = 0;
                    break;
                case 2:
                    $this->service->with_input = 1;
                    $this->service->with_days = 0;
                    $this->service->no_attendances = null;
                    break;
                default:
                    $this->service->with_input = 0;
                    $this->service->with_days = 0;
                    $this->service->no_attendances = null;
                    break;
            }

            if ($this->image) {
                $date = date("Y-m-d");
                $imageName = $this->image->store("services/".$date,'public');
                $this->service->update(['image' => $imageName]);
            }

            $this->service->save($validatedData);

            $this->emit('refreshIndex');
            $this->image = null;

            $this->alert('success', __('Service updated successfully.'));

            $this->editModal = false;
        } catch (Throwable $th) {
            $this->alert('error', __('Error.').$th->getMessage());
        }
    }

    public function render()
    {
        abort_if(Gate::denies('service_update'), 403);

        return view('livewire.services.edit');
    }
}
