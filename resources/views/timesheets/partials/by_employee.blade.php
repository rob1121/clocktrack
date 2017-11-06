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
                @foreach($employees as $employee)
                        <tr>
                            <td>
                            <span>{{$employee->fullname}}</span>
                            </td>
                            @foreach($week as $day)
                                <td>
                                    @php
                                        $biometric = $employee->biometric();
                                        $biometric = $biometric->where('time_in', 'LIKE', "{$day->format('Y-m-d')}%")->get();
                                        $hasBreakTime = $biometric->pluck('breaktime')->filter(function($bt) { return $bt->isNotEmpty();})->isNotEmpty();
                                    @endphp
                                    @if($biometric->isNotEmpty())
                                        @php
                                            $duration = hourMinuteFormat($biometric->first()->time_in) . ' - ' . hourMinuteFormat($biometric->last()->time_out);
                                        @endphp
                                        <div class="alert alert-warning">
                                            <div class="sticky">
                                            @if($hasBreakTime)
                                                <p><i class="fa fa-cutlery fa-2x"></i></p>
                                            @endif
                                            <a href="{{route('schedule.by_employee', ['user' => $employee->id, 'date' => $day->format('Y-m-d')])}}" class="text-success">
                                                <i class="fa fa-edit fa-2x"></i>
                                            </a>
                                            <h4>
                                                {{minutesToHourMinuteFormat($biometric->sum('duration_in_minutes'))}}
                                                <small>hh:mm</small>
                                            </h4>
                                            <small>{{$duration}}</small>
                                            <small>{{$biometric->implode('job', ', ')}}</small>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                            <td>
                                <div class="alert alert-warning"
                                @if($employee->biometric->isEmpty())
                                    hidden
                                @endif
                                >
                                    <div class="sticky">
                                        <h4>
                                        @if(!empty($employee->biometric))
                                            {{minutesToHourMinuteFormat($employee->biometric->sum('duration_in_minutes'))}}
                                        @else
                                            00:00
                                        @endif
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
                            $totalMinutes = $biometrics->filter(function($bt) use($day) {
                                return str_contains($bt, $day->format('Y-m-d'));
                            })->sum('duration_in_minutes');
                        @endphp
                            <td>
                            <h4>{{$totalMinutes ? (minutesToHourMinuteFormat($totalMinutes) . ' hh:mm') : ''}}</h4>
                            </td>
                        @endforeach
                        <td>
                            <h4>{{minutesToHourMinuteFormat($biometrics->sum('duration_in_minutes'))}} hh:mm</h4>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
