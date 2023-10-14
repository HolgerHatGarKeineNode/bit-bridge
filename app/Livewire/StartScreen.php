<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;

class StartScreen extends Component
{
    public array $campaigns = [];

    public function mount()
    {
        $this->campaigns = Task::query()->get()->toArray();
    }

    public function render()
    {
        return view('livewire.start-screen');
    }
}
