<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;

class TaskView extends Component
{
    public Task $task;

    public array $emails = [];

    public function mount()
    {
        $this->emails = $this->task
            ->emails()
            ->with(['emailAddress'])
            ->orderBy('send_at')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.task-view');
    }
}
