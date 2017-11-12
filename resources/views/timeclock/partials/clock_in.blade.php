<div class="row">
  <div class="col-md-2 col-md-offset-2">
    <p class="text-center">
      Hours
    </p>
    <h1 id="hours" class="text-center">00</h1>
  </div>
  <div class="col-md-2">
    <p class="text-center">
      Minutes
    </p>
    <h1 id="minutes" class="text-center">00</h1>
  </div>
  <div class="col-md-2">
    <p class="text-center">
      Seconds 
    </p>
    <h1 id="seconds" class="text-center">00</h1>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <form action="{{route('timeclock.store')}}" method="post" enctype="multipart/form-data">
      {{csrf_field()}}
      <input type="hidden" name="active" id="active" value="1">
      <input type="hidden" name="start_date" id="start_date">
      <input type="hidden" name="start_time" id="start_time">
      <input type="hidden" name="end_date" id="end_date">
      <input type="hidden" name="end_time" id="end_time">
      <input type="hidden" name="lng" id="lng">
      <input type="hidden" name="lat" id="lat">
      <input type="hidden" name="employees" id="employees" value="{{Auth::user()->id}}">
      <div class="form-group">
        <label>Job</label>
        @component('components.select', [
          'options' => $jobOptions,
          'name' => 'job',
          'value' => old('job'),
          'id' => 'job',
        ])
        @endcomponent
      </div>

      <div class="form-group">
        <label>Task</label>
        @component('components.select', [
          'options' => $taskOptions,
          'name' => 'task',
          'value' => old('task'),
          'id' => 'task',
        ])
        @endcomponent
      </div>

      

      <div class="form-group">
        <label class="control-label">Attachment:</label>
        <input type="file" name="file" id="file">
      </div>

      <div class="form-group">
        <label class="control-label">Notes(Optional)</label>
        @component('components.textarea',[
          'name' => 'notes', 
          'value' => old('notes'),
          'id' => 'notes',
        ])
        @endcomponent
      </div>

      <div class="form-group" hidden>
          <label>Attachment</label>
          <input type="file" name="file" id="file">
      </div>

      <div class="form-group">
          <button class="btn btn-primary btn-lg btn-block">
            <i class="fa fa-play"></i>
            <span>Clock In</span>
          </button>
      </div>
    </form>
  </div>
</div>
@push('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAoEgrLsTgTTnvtMXVcwe9PCabdnk3PtUI"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.25/gmaps.min.js"></script>
  <script>
    $(document).ready(function() {
      setInterval(function() {
        $('#start_date').val(moment().format('Y-MM-DD'));
        $('#end_date').val(moment().format('Y-MM-DD'));

        $('#start_time').val(moment().format('HH:mm:ss'));
        $('#end_time').val(moment().format('HH:mm:ss'));
      }, 1000);

      GMaps.geolocate({
        success: function(position) {
          $('#lat').val(position.coords.latitude);
          $('#lng').val(position.coords.longitude);
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