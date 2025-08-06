<?php

use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use App\Models\Event;
use \Illuminate\Support\Str;

new class extends Component {
    public $event = [];

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function with(): array
    {
        $date = Carbon::createFromTimestamp($this->event->date);
        $monthName = $date->format('F');
        $yearNumber = $date->year;
        $daysInMonth = $date->daysInMonth;
        $startDayOfWeek = $date->dayOfWeek;
        $blankDays = $startDayOfWeek;

        $calendarDays = [];

        for ($i = 0; $i < $blankDays; $i++) {
            $calendarDays[] = null;
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $calendarDays[$day] = [
                'day' => $day,
                'timestamp' => Carbon::create($yearNumber, $date->month, $day)->getTimestamp(),
            ];
        }

        return [
            'monthName' => $monthName,
            'year' => $yearNumber,
            'calendarDays' => $calendarDays,
        ];
    }
};
?>

<div class="flex flex-col gap-10">
    <div class="flex flex-col gap-2">
        <h2 class="font-bold text-lg">{{ $event->name }}</h2>
        <p>{{ Carbon::createFromTimestamp($event->date)->format('Y-M-d') }}</p>
        <p>{{ $event->location }}</p>
    </div>

    <div>
        <h3 class="text-lg font-bold">Volunteers Roles:</h3>

        <ul class="grid auto-cols-auto gap-5">
            @foreach($event->roles as $role)
                <li class="border border-zinc-300 rounded-lg p-4">
                    <p>{{ Str::apa($role['role_name']) }}</p>
                    <p>Frequency: {{ $role['frequency'] }}</p>
                    <p>Starting on: {{ $role['start_date'] }}</p>
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

    {{--    @dump($event->date, $calendarDays)--}}

    <div class="calendar-container w-full bg-white rounded-lg shadow-xl p-6">

        <div class="calendar-header flex justify-between items-center mb-6">
            <div class="calendar-nav">
                <button wire:click="previousMonth"
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
                <div class="h-20 flex flex-col items-center justify-center p-2 rounded-lg
                    @if (is_null($day))
                        bg-gray-100 text-gray-400
                    @else
                        bg-white text-gray-900 border border-gray-200 hover:bg-blue-50 transition-colors duration-200
                    @endif
                ">
                    @if (!is_null($day))
                        <span class="text-xl font-bold">
                            {{ $day['day'] }}
                        </span>
                        <span class="text-xs">Day: {{ $day['timestamp'] }}</span>
                        {{--                        @if (Carbon::parse($day['timestamp'])->equalTo($event->date))--}}
                        {{--                            SAME--}}
                        {{--                        @endif--}}
                        <span class="text-xs">Event: {{ Carbon::parse($event->date)->format('M-d-Y') }}</span>
                    @endif
                </div>
            @endforeach
        </div>

    </div>
</div>
