<?php

use Livewire\Volt\Component;
use App\Models\Event;

new class extends Component {
    public $events = [];

    public function mount()
    {
        $this->events = Event::all();
    }
}; ?>

<div>
    Events

    <ul class="grid grid-cols-1 md:grid-cols-3 gap-3">
        @foreach($events as $event)
            <li class="border border-zinc-300 rounded-2xl p-3">
                <h2 class="font-bold text-lg">{{ $event->name }}</h2>
                <p>{{ $event->date }}</p>
                <p>{{ $event->location }}</p>

                <div class="flex justify-end">
                    <a href="{{route('events.show', $event)}}">View2</a>
                </div>
            </li>
        @endforeach
    </ul>
</div>
