<div class="row" id="byJob" hidden>
    <div class="col-md-12">
        @if (session('status'))
            @component('components.alert', ['title' => 'Schedule Added', 'icon' => 'check-circle', 'type' => 'success' ])
            <p>{{session('status')}}</p>
            @endcomponent
        @endif
        
        @if($schedules->isNotEmpty())
            <div class="panel panel-default">
                <table class="table table-bordered" id="ByEmployeeTable">
                    <thead>
                        <tr>
                            <th></th>
                            @foreach($week as $day)
                                <th>{{$day->format('D m/d')}}</th>
                            @endforeach
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($schedules->unique('job') as $schedule)
                        <tr>
                            <td>
                                <span>{{$schedule->job}}</span>
                            </td>
                                @foreach($week as $day)
                                    <td>
                                        @php
                                            $resultSet = $schedules->where('job', $schedule->job)->where('start_date', $day->format('Y-m-d'));
                                        @endphp
                                        @if($resultSet->isNotEmpty())
                                            <div class="alert alert-warning">
                                                <div class="sticky">
                                                <a href="{{route('schedule.by_job', ['job' => $schedule->job, 'date' => $day->format('Y-m-d')])}}" class="text-success">
                                                    <p>
                                                        <i class="fa fa-edit fa-2x"></i>
                                                    </p>
                                                </a>
                                                <h4>
                                                    {{minutesToHourMinuteFormat($resultSet->sum('duration_in_minutes'))}}
                                                    <small>hh:mm</small>
                                                </h4>
                                                <small>{{$resultSet->first()->start_time}} - {{$resultSet->last()->end_time}}</small>
                                                <small>{{$resultSet->pluck('user.fullname_with_no_comma')->implode(', ')}}</small>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                                <td>
                                    <div class="alert alert-warning">
                                        <div class="sticky">
                                            <h4>
                                                {{
                                                    minutesToHourMinuteFormat($schedules->where('job', $schedule->job)->sum('duration_in_minutes'))
                                                }}
                                                <small>hh:mm</small>
                                            </h4>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <td>Total</td>
                            @foreach($week as $day)
                                @php
                                $totalMinutes = $schedules->where('start_date', $day->format('Y-m-d'))->sum('duration_in_minutes');
                                @endphp
                                <td>
                                <h4>{{$totalMinutes ? (minutesToHourMinuteFormat($totalMinutes) . ' hh:mm') : ''}}</h4>
                                </td>
                            @endforeach
                            <td>
                                <h4>{{minutesToHourMinuteFormat($schedules->sum('duration_in_minutes'))}} hh:mm</h4>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            @component('components.alert', ['title' => 'No Time to Display!', 'icon' => 'info-circle', 'type' => 'info' ])
                <p>Use the 'Add Time' button above or switch to the 'By Employee' view.</p>
            @endcomponent
        @endif
    </div>
</div>