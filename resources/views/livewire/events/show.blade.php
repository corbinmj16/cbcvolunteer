<?php

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use App\Models\Event;
use \Illuminate\Support\Str;

new class extends Component {
    public Event $event;
    public string $monthName;
    public int $year;
    public Carbon $currentDate;
    public array $calendarDays = [];

    public function mount(Event $event)
    {
        $this->currentDate = Carbon::parse($event->date);
        $this->event = $event;
        $this->monthName = $this->currentDate->format('F');
        $this->year = $this->currentDate->year;

        $this->setCalendar();
    }

    public function prevMonth()
    {
        $this->currentDate->subMonth()->format('F');
        $this->setCalendar();
    }

    public function nextMonth()
    {
        $this->currentDate->addMonth()->format('F');
        $this->setCalendar();
    }

    public function setCalendar()
    {
        $this->calendarDays = [];
        $this->monthName = $this->currentDate->format('F');
        $this->year = $this->currentDate->year;
        $daysInMonth = $this->currentDate->daysInMonth;
        $startDayOfWeek = $this->currentDate->dayOfWeek;
        $blankDays = [];
        $event_date = Carbon::parse($this->event->date)->format('Y-m-d');

        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $blankDays[] = null;
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $datetime = Carbon::create($this->year, $this->currentDate->month, $day)->format('Y-m-d');

            // Gather all roles that need help on this date
            $roles_needed = [];

            foreach ($this->event->roles as $role) {
                if (in_array($datetime, $role['needed'])) {
                    $roles_needed[] = $role; // or include more info if needed
                }
            }

            $this->calendarDays[$day] = [
                'day' => $day,
                'datetime' => $datetime,
                'event' => $datetime === $event_date ? $this->event : null,
                'roles_needed' => $roles_needed, // this will be an array, possibly empty
            ];
        }

        $this->calendarDays = [...$blankDays, ...$this->calendarDays];
    }
};
?>

<div class="flex flex-col gap-10">
    <div class="flex flex-col gap-2">
        <h2 class="font-bold text-lg">{{ $event->name }}</h2>
        <p>{{ Carbon::parse($event->date)->format('D, M d, Y') }}</p>
        <p>{{ $event->location }}</p>
    </div>

    <div>
        <h3 class="text-lg font-bold">Volunteers Roles:</h3>

        <ul class="flex gap-5">
            @foreach($event->roles as $role)
                <li class="border border-zinc-300 rounded-lg p-4">
                    <p>{{ Str::apa($role['role_name']) }}</p>
                    <p>{{ $role['description'] }}</p>
                    <ul class="list-disc pl-4">
                        <li class="list-none">Needed On:</li>
                        @foreach($role['needed'] as $date)
                            <li>{{ $date }}</li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="calendar-container w-full bg-white rounded-lg shadow-xl p-6">

        <div class="calendar-header flex justify-between items-center mb-6">
            <div class="calendar-nav">
                <button wire:click="prevMonth"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                    &lt; Prev
                </button>
            </div>
            <h2 class="text-2xl font-semibold text-gray-800">{{ $monthName }} {{ $year }}</h2>
            <div class="calendar-nav">
                <button wire:click="nextMonth"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                    Next &gt;
                </button>
            </div>
        </div>

        <div class="grid grid-cols-7 text-center font-bold text-gray-600 mb-2">
            <div>Sun</div>
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div>Sat</div>
        </div>

        <div class="grid grid-cols-7 gap-1">
            @foreach ($calendarDays as $day)
                <div class="h-24 flex flex-col justify-items-start p-2 rounded-lg
                    @if (is_null($day))
                        bg-gray-100 text-gray-400
                    @else
                        bg-white text-gray-900 border border-gray-200 hover:bg-blue-50 transition-colors duration-200
                    @endif
                ">
                    @if (!is_null($day))
                        <span class="font-bold">
                            {{ $day['day'] }}
                        </span>
                        @if (!is_null($day['event']))
                            <span class="font-bold">🎉 {{ $day['event']->name }} 🎉</span>
                        @endif
                        @foreach($day['roles_needed'] as $role)
                            <span>💪 {{ $role['role_name'] }}</span>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>

    </div>
</div>
