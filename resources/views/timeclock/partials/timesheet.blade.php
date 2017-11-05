<div class="container-fluid">

@php 
    $start = $week[0];
    $end = $week[count($week)-1];
    $isNotNextMonth = $start->format('M') === $end->format('M');
@endphp

<div class="row">

    <div class="row form-group">
        <div class="col-xs-2">
            <div class="btn-group">
                <a 
                    href="{{route('timeclock.timesheet', [
                        'user' => Auth::user()->id,
                        'start' => $start->modify('-1 week')->format('Y-m-d'),
                        'end' => $end->modify('-1 week')->format('Y-m-d'), 
                    ])}}"
                    class="btn btn-primary" 
                >
                    <i class="fa fa-angle-left"></i>
                </a>
                <a 
                    href="{{route('timeclock.timesheet', [
                        'user' => Auth::user()->id,
                        'start' => $start->modify('2 week')->format('Y-m-d'),
                        'end' => $end->modify('2 week')->format('Y-m-d'), 
                    ])}}"
                    class="btn btn-primary"
                >
                    <i class="fa fa-angle-right"></i>
                </a>
            </div>
        </div>
</div>
@if($schedules->isEmpty())
    <div class="row">
        <div class="col-md-12">
        <p id="noTimesheetFoundComponent"><i>No Time Sheet data to display</i></p>
        </div>
    </div>
@else
    <div class="row">
    <div class="col-xs-6">
        <h1>
            {{$start->format('M d')}}
            -
            {{$end->format($isNotNextMonth ? 'd Y' : 'M d Y')}}
        </h1>
    </div>
    <table class="table table-hover" id="timesheetTable">
    <thead>
        <tr>
        <th>Job</th>
        <th>Task</th>
        @foreach($week as $day)
            <th>{{$day->format('D')}}</th>
        @endforeach
        <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($schedules as $schedule)
            <tr>
                <td>{{$schedule->job}}</td>
                <td>{{$schedule->task}}</td>
                @foreach($week as $day)
                <td>
                    {{($day->format('D') === $schedule->date) ? minutesToHourMinuteFormat($schedule->duration) : '-'}}
                </td>
                @endforeach
                <td>{{minutesToHourMinuteFormat($schedule->duration)}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2"></td>
            @foreach($week as $day)
                @php
                    $dailyTotal = $schedules->where('date', $day->format('D'))->sum('duration');
                @endphp
                <td>
                    {{$dailyTotal ? minutesToHourMinuteFormat($dailyTotal): '-'}}
                </td>
            @endforeach
            <td>{{minutesToHourMinuteFormat($schedules->sum('duration'))}}</td>
        </tr>
    </tbody>
    </table>
    </div>
@endif
</div>