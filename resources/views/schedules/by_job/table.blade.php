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
              <th>Employee</th>
              <th>Task</th>
              <th>Time</th>
              <th></th>
          </tr>
      </thead>
      <tbody>
          @if($schedules->isNotEmpty())
              @foreach($schedules as $schedule)
                  <tr>
                      <td>{{hourMinuteFormat($schedule->start_time)}}</td>
                      <td>{{hourMinuteFormat($schedule->end_time)}}</td>
                      <td>{{$schedule->user->fullname}}</td>
                      <td>{{$schedule->task}}</td>
                      <td>{{minutesToHourMinuteFormat($schedule->duration_in_minutes)}}</td>
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
          @else
              <tr>
                  <td colspan="6" class="text-center">No Schedule found</td>
              </tr>
          @endif
      </tbody>
  </table>
</div>