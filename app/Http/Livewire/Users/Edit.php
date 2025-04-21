<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Models\User;
use App\Models\Role;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Edit extends Component
{
    use LivewireAlert;

    public $listeners = [
        'editModal',
    ];
    public $editModal = false;

    public $user;

    public $name;

    public $email;

    public $password;

    public $password_confirmation;

    public $phone;

    public $role;

    public $warehouse_id;

    /** @var array */
    protected $rules = [
        'name'         => 'required|string|min:3|max:255',
        'email'        => 'required|email',
        'password' => 'nullable|string|min:8|confirmed', // nullable y confirmed
        'password_confirmation' => 'nullable|string|min:8', 
        'phone'        => 'required|numeric',
        'role'         => 'required',
        'warehouse_id' => 'required|array',
    ];

    public function editModal($id)
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->user = User::where('id', $id)->firstOrFail();

        $this->name = $this->user->name;

        $this->email = $this->user->email;

        $this->phone = $this->user->phone;

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        if (!empty($this->password)) {
            $this->user->password = Hash::make($this->password);
        }

        $this->user->save();

        // UserWarehouse::update([
        //     'user_id'      => $this->id,
        //     'warehouse_id' => $this->warehouse_id,
        // ]);

        $this->alert('success', __('User Updated Successfully'));

        $this->emit('refreshIndex');

        $this->editModal = false;
    }

    public function render()
    {
        abort_if(Gate::denies('user_edit'), 403);

        return view('livewire.users.edit');
    }

    public function getRolesProperty()
    {
        return Role::pluck('name', 'id')->toArray();
    }

    public function getWarehousesProperty()
    {
        return Warehouse::pluck('name', 'id')->toArray();
    }
}
