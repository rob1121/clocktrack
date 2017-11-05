@if (session('deleted'))
    @component('components.alert', ['title' => 'Schedule Deleted', 'icon' => 'check-circle', 'type' => 'success' ])
    <p>{{session('deleted')}}</p>
    @endcomponent
@elseif (session('updated'))
    @component('components.alert', ['title' => 'Schedule Updated', 'icon' => 'check-circle', 'type' => 'success' ])
    <p>{{session('updated')}}</p>
    @endcomponent
@endif

<div class="panel panel-default">
  <table class="table table-hover">
      <thead>
          <tr>
              <th>In</th>
              <th>Out</th>
              <th>Job</th>
              <th>Task</th>
              <th>Time</th>
              <th></th>
          </tr>
      </thead>
      <tbody>
          @if($user->schedule->isNotEmpty())
              @foreach($user->schedule as $schedule)
                  <tr>
                      <td>{{hourMinuteFormat($schedule->start_time)}}</td>
                      <td>{{hourMinuteFormat($schedule->end_time)}}</td>
                      <td>{{$schedule->job}}</td>
                      <td>{{$schedule->task}}</td>
                      <td>{{minutesToHourMinuteFormat($schedule->duration_in_minutes + $schedule->breaktime->sum('duration_in_minutes'))}}</td>
                      <td>
                      <a href="{{route('schedule.edit', ['schedule' => $schedule->id])}}" class="btn btn-primary btn-xs">
                          <i class="fa fa-edit"></i>
                      </a>
                      @component('components.delete_button', [
                        'model' => 'schedule',
                        'modelId' => $schedule->id,
                      ])
                      @endcomponent
                      </td>
                  </tr>
              @endforeach
              @foreach($user->schedule->pluck('breaktime')->flatten() as $breaktime)
                  <tr class="bg-warning">
                      <td colspan="4">Lunch Break: {{Carbon::parse($breaktime->break_in)->format('h:i a')}} -  {{Carbon::parse($breaktime->break_out)->format('h:i a')}}</td>
                      <td>({{minutesToHourMinuteFormat($breaktime->duration_in_minutes)}})</td>
                      <td></td>
                  </tr>
              @endforeach
            <tr class="bg-info">
                <td colspan="4"></td>
                <td><strong>{{minutesToHourMinuteFormat($user->schedule->sum('duration_in_minutes'))}}</strong></td>
                <td></td>
            </tr>
          @else
              <tr>
                  <td colspan="6" class="text-center">No Schedule found</td>
              </tr>
          @endif
      </tbody>
  </table>
</div>