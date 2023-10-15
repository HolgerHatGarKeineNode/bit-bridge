<?php

namespace App\Livewire;

use App\Models\Tag;
use App\Models\Task;
use App\Models\Text;
use Livewire\Component;

class StartScreen extends Component
{
    public array $campaigns = [];

    public function mount()
    {
        $this->campaigns = Task::query()
            ->with(['emails'])
            ->get()
            ->toArray();
    }

    public function poll()
    {
        $this->campaigns = Task::query()
            ->with(['emails'])
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.start-screen', [
            'countLists' => Tag::query()->count(),
            'countTexts' => Text::query()->count(),
        ]);
    }
}
