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

class Create extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $createModal = false;

    /** @var mixed */
    public $service;

    public $image;

    /** @var array<string> */
    public $listeners = ['createModal'];

    /** @var array */
    protected $rules = [
        'service.name'        => 'required|min:3|max:50',
        'service.note' => 'nullable|min:3',
        'service.price' => 'required|integer|min:1',
        'service.service_type' => 'required',
        'image' => 'nullable|mimes:jpeg,png,jpg,gif|size:2024',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function createModal(): void
    {
        abort_if(Gate::denies('service_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->service = new Service();

        $this->createModal = true;
    }

    public function create(): void
    {
        try {
            $validatedData = $this->validate();

            if ($this->image) {
                // $imageName = Str::slug($this->service->name).'-'.Str::random(5).'.'.$this->image->extension();
                // $this->image->storeAs('services', $imageName);
                // $this->image = $imageName;
                $date = date("Y-m-d");
                $imageName = $this->image->store("services/".$date,'public');
                $this->service->image = $imageName;
            }

            $this->service->save($validatedData);

            $this->emit('refreshIndex');

            $this->alert('success', __('Service created successfully.'));

            $this->createModal = false;
        } catch (Throwable $th) {
            $this->alert('success', __('Error.').$th->getMessage());
        }
    }

    public function render()
    {
        abort_if(Gate::denies('service_create'), 403);

        return view('livewire.services.create');
    }
}
