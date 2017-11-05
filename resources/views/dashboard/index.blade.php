@extends('layouts.app')

@section('content')
      <div class="container-fluid">
        <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
          Who's Working Now ?
          </div>
            @if($active->isEmpty())
                <div class="panel-body" style="min-height: 500px">
                    No employees are clocked in
                </div>
            @else  
                @foreach($active as $schedule)
                <div class="list-group">
                    <a href="#" class="list-group-item">
                        <strong>{{$schedule->user->fullname}}</strong>
                        <form action="{{route('dashboard.force_clockout', ['schedule' => $schedule->id])}}" method="POST">
                            {{csrf_field()}}
                            <button type="submit" class="btn btn-xs btn-default pull-right">
                                <i class="fa fa-remove"></i>
                            </button>
                        </form>
                        <p>
                            <small>{{Carbon::parse($schedule->start_datetime)->diffForHumans()}}</small>
                            <br/>
                            <small>{{$schedule->job}}</small>
                            <br/>
                            <small>{{$schedule->task}}</small>
                        </p>
                    </a>
                </div>
                
                @endforeach
            @endif
        </div>
      </div>
      <div class="col-md-8">
        <div class="panel panel-default">
          <div class="panel-heading">
            Clocktrack Locations
          </div>
          <div id="map"></div>
        </div>
        </div>
    </div>
@endsection


@push('script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAoEgrLsTgTTnvtMXVcwe9PCabdnk3PtUI"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.25/gmaps.min.js"></script>
<script>
 $(document).ready(function () {
    var map = new GMaps({
        div: '#map',
        lat: 0,
        lng: 0,
        width: '100%',
        height: '500px',
        zoom: 2,
        zoomControl: true,
        zoomControlOpt: {
            style: 'SMALL',
            position: 'TOP_LEFT'
        },
        panControl: false
    });

    @foreach($active as $employee)
        map.addMarker({
            lat: @json($employee->lat),
            lng: @json($employee->lng),
            title: @json($employee->user->fullname),
            click: function(e) {
                alert("Employee: {{$employee->user->fullname}}\n\n\nJob: {{$employee->job}}\nTask: {{$employee->task}}");
            }
        });
    @endforeach
});
</script>
@endpush
