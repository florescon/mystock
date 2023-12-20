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
    public $service;

    public $image;

    /** @var array<string> */
    public $listeners = ['editModal'];

    /** @var array */
    protected $rules = [
        'service.name'        => 'required|string||min:3|max:50',
        'service.note' => 'nullable|string',
        'service.price' => 'required|integer|min:1',
        'service.service_type' => 'required',
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
