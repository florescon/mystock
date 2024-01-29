<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Models\User;
use App\Rules\MatchCurrentPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Profile extends Component
{
    use LivewireAlert;

    /** @var mixed */
    public $user;

    public $name;
    public $phone;
    public $email;
    public $image;
    public $password;
    public $current_password;
    public $password_confirmation;

    /** @var array */
    public function rules()
    {
        return [ 
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,'.Auth::user()->id,
            'phone'    => 'required|numeric',
        ];
    }

    public function mount(): void
    {
        $this->user = User::find(Auth::user()->id);
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
    }

    public function render()
    {
        return view('livewire.users.profile');
    }

    public function update(): void
    {
        $this->validate();

        auth()->user()->update([
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        if (isset($this->image)) {
            $this->image->store('users', 'public');
            auth()->user()->update([
                'image' => $this->image->hashName(),
            ]);
        }

        $this->alert('success', __('Profile updated successfully!'));
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'max:255'],
            'password'         => 'required|min:8|max:255|confirmed',
        ]);

        if(!Hash::check($this->current_password, auth()->user()->password)){
            $this->alert('error', __("Current Password Doesn't match"), ['position' => 'top']);

            return;
        }

        auth()->user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->alert('success', __('Password updated successfully!'));
    }
}
