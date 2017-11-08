
<table class="table table-hover">
  <thead>
    <tr>
      <th>In</th>
      <th>Out</th>
    </tr>
  </thead>
  <tbody>
  @foreach(Auth::user()->biometric->sortByDesc('time_out') as $biometric)
    <tr>
      <td>{{logDateTimeFormat($biometric->time_in)}}</td>
      <td>{!!$biometric->active ? "<i>In progress..</i>" : logDateTimeFormat($biometric->time_out)!!}</td>
    </tr>
  @endforeach
  </tbody>
</table>
