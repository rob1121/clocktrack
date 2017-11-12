@if (session('deleted'))
    @component('components.alert', ['title' => 'biometric Deleted', 'icon' => 'check-circle', 'type' => 'success' ])
    <p>{{session('deleted')}}</p>
    @endcomponent
@elseif (session('updated'))
    @component('components.alert', ['title' => 'biometric Updated', 'icon' => 'check-circle', 'type' => 'success' ])
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
          @if($user->biometric->isNotEmpty())
              @foreach($user->biometric as $biometric)
                  <tr>
                      <td>{{hourMinuteFormat($biometric->time_in)}}</td>
                      <td>{{hourMinuteFormat($biometric->time_out)}}</td>
                      <td>{{$biometric->job}}</td>
                      <td>{{$biometric->task}}</td>
                      <td>{{minutesToHourMinuteFormat($biometric->duration_in_minutes + $biometric->breaktime->sum('duration_in_minutes'))}}</td>
                      <td>
                      <a href="{{route('biometric.edit', ['biometric' => $biometric->id])}}" class="btn btn-primary btn-xs">
                          <i class="fa fa-edit"></i>
                      </a>
                      @component('components.delete_button', [
                        'model' => 'biometric',
                        'modelId' => $biometric->id,
                      ])
                      @endcomponent
                      </td>
                  </tr>
              @endforeach
              @foreach($user->biometric->pluck('breaktime')->flatten() as $breaktime)
                  <tr class="bg-warning">
                      <td colspan="4">Lunch Break: {{Carbon::parse($breaktime->break_in)->format('h:i a')}} -  {{Carbon::parse($breaktime->break_out)->format('h:i a')}}</td>
                      <td>({{minutesToHourMinuteFormat($breaktime->duration_in_minutes)}})</td>
                      <td></td>
                  </tr>
              @endforeach
            <tr class="bg-info">
                <td colspan="4"></td>
                <td><strong>{{minutesToHourMinuteFormat($user->biometric->sum('duration_in_minutes'))}}</strong></td>
                <td></td>
            </tr>
          @else
              <tr>
                  <td colspan="6" class="text-center">No biometric found</td>
              </tr>
          @endif
      </tbody>
  </table>
</div>