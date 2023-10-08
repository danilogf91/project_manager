<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class DeleteUsers extends Component
{
    public $user;

    public $openModal = false;

    public function delete(User $user)
    {
        $user->delete();
        $this->openModal = false;
        $this->dispatch('user-deleted');
    }

    public function mount($user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.delete-users');
    }
}
