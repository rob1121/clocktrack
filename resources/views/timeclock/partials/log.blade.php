
<table class="table table-hover">
  <thead>
    <tr>
      <th>In</th>
      <th>Out</th>
    </tr>
  </thead>
  <tbody>
  @foreach(Auth::user()->schedule as $schedule)
    <tr>
      <td>{{logDateTimeFormat($schedule->start_datetime)}}</td>
      <td>{!!$schedule->active ? "<i>In progress..</i>" : logDateTimeFormat($schedule->end_datetime)!!}</td>
    </tr>
  @endforeach
  </tbody>
</table>
