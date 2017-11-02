@if (session('status'))
    @component('components.alert', ['title' => 'Schedule Deleted', 'icon' => 'check-circle', 'type' => 'success' ])
    <p>{{session('status')}}</p>
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
                      <td>{{minutesToHourMinuteFormat($schedule->duration_in_minutes)}}</td>
                      <td>
                      <button class="btn btn-primary btn-xs">
                          <i class="fa fa-edit"></i>
                      </button>
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