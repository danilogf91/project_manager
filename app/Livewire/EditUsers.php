<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Rule;

class EditUsers extends Component
{
    public User $user;
    public $openModal = false;

    #[Rule('required|string|max:255')]
    public $name = '';

    // #[Rule('required|email|unique:users,email')]
    public $email = '';

    #[Rule('in:0,1')]
    public $is_admin;

    #[Rule('in:0,1')]
    public $active;

    public $created_at;

    public function update(User $user)
    {
        $this->validate();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->is_admin = $this->is_admin;
        $user->active = $this->active;
        $user->save();
        $this->openModal = false;
        $this->dispatch('edit-users');
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_admin = $user->is_admin;
        $this->active = $user->active;
        $this->created_at = date("Y-m-d", strtotime($user->created_at));
    }

    public function render()
    {
        return view('livewire.edit-users');
    }
}
