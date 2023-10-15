<?php

namespace App\Livewire;

use App\Models\Email;
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

    public function poll()
    {
        $this->emails = $this->task
            ->emails()
            ->with(['emailAddress'])
            ->orderBy('send_at')
            ->get()
            ->toArray();
    }

    public function delete($id)
    {
        Email::query()
            ->where('id', $id)
            ->delete();

        $this->emails = $this->task
            ->emails()
            ->with(['emailAddress'])
            ->orderBy('send_at')
            ->get()
            ->toArray();
    }

    public function play()
    {
        $this->task->update([
            'status' => 'running',
        ]);
        $this->task->refresh();
    }

    public function pause()
    {
        $this->task->update([
            'status' => 'paused',
        ]);
        $this->task->refresh();
    }

    public function render()
    {
        return view('livewire.task-view');
    }
}
