@extends('layouts.app')

@section('content')
    <div class="container-fluid">

    <div class="row form-group">
        <div class="col-xs-2">
            <div class="btn-group">
                <a 
                    href="{{route('timesheet.by_employee', [
                        'user' => $user->id, 
                        'date' => Carbon::parse(Request::get('date'))->modify('-1 day')->format('Y-m-d')
                    ])}}"
                    id="prev"
                    class="btn btn-primary" 
                >
                    <i class="fa fa-arrow-left"></i>
                    <span>Previous</span>
                </a>
                <a 
                    href="{{route('timesheet.by_employee', [
                        'user' => $user->id, 
                        'date' => Carbon::parse(Request::get('date'))->modify('1 day')->format('Y-m-d')
                    ])}}"
                    id="next"
                    class="btn btn-primary"
                >
                    <span>Next</span>
                    <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="col-xs-2">
            <a 
            href="{{route('timesheet.create')}}"
            id="next"
            class="btn btn-success"
            >
                <i class="fa fa-plus"></i>
                <span>Add time</span>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h1>{{$user->fullname}}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-primary">{{Carbon::parse(Request::get('date'))->format('D m/d')}}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div id="map" style="border:1px solid black;position: relative;left:0;right:0;bottom:0;top:0">adad</div>
        </div>
        <div class="col-md-8">
            @include('timesheets.by_employee.partials.table')
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
        lat: 51.5073346,
        lng: -0.1276831,
        width: '100%',
        height: '500px',
        zoom: 12,
        zoomControl: true,
        zoomControlOpt: {
            style: 'SMALL',
            position: 'TOP_LEFT'
        },
        panControl: false
    });

    GMaps.geolocate({
        success: function(position) {
            map.setCenter(position.coords.latitude, position.coords.longitude);
        },
        error: function(error) {
            alert('Geolocation failed: '+error.message);
        },
        not_supported: function() {
            alert("Your browser does not support geolocation");
        }
    });
});
</script>
@endpush