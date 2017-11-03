@extends('layouts.app')

@section('content')
      <div class="container-fluid">
        <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
          Who's Working Now ?
          </div>
          <div class="panel-body">
            No employees are clocked in
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="panel panel-default">
          <div class="panel-heading">
            Clocktrack Locations
          </div>
          <div id="map" style="border:1px solid black;position: relative;left:0;right:0;bottom:0;top:0"></div>
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
