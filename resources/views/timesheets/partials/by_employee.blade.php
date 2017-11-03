<div class="row" id="byEmployee">
    <div class="col-md-12">
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
                @foreach($users as $user)
                        <tr>
                            <td>
                            <span>{{$user->fullname}}</span>
                            </td>
                            @foreach($week as $day)
                                <td>
                                    @php
                                        $resultSet = $user->schedule->where('start_date', $day->format('Y-m-d'));
                                    @endphp
                                    @if($resultSet->isNotEmpty())
                                            <div class="alert alert-warning">
                                                <div class="sticky">
                                                <a href="{{route('schedule.by_employee', ['user' => $user->id, 'date' => $day->format('Y-m-d')])}}" class="text-success">
                                                    <i class="fa fa-edit fa-2x"></i>
                                                </a>
                                                <h4>
                                                    {{minutesToHourMinuteFormat($resultSet->sum('duration_in_minutes'))}}
                                                    <small>hh:mm</small>
                                                </h4>
                                                <small>{{hourMinuteFormat($resultSet->first()->start_time)}} - {{hourMinuteFormat($resultSet->last()->end_time)}}</small>
                                                <small>{{$resultSet->implode('job', ', ')}}</small>
                                                </div>
                                            </div>
                                    @endif
                                </td>
                            @endforeach
                            <td>
                                <div class="alert alert-warning"
                                @if($user->schedule->isEmpty())
                                    hidden
                                @endif
                                >
                                    <div class="sticky">
                                        <h4>
                                            {{
                                                minutesToHourMinuteFormat($user->schedule->sum('duration_in_minutes'))
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
    </div>
</div>