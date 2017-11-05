@if(Auth::user()->schedule->isnotEmpty())
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="col-md-4">
        <p class="text-center">
          Hours
        </p>
        <h1 id="hours" class="text-center">00</h1>
      </div>
      <div class="col-md-4">
        <p class="text-center">
          Minutes
        </p>
        <h1 id="minutes" class="text-center">00</h1>
      </div>
      <div class="col-md-4">
        <p class="text-center">
          Seconds 
        </p>
        <h1 id="seconds" class="text-center">00</h1>
      </div>
    </div>
  </div>

  <div class="row" id="workingStatusContainer"
  @if($last_schedule->breaktime->isNotEmpty() && !$last_schedule->breaktime->last()->break_out)
    hidden
  @endif
  >
    <p class="text-center">{{logDateTimeFormat($last_schedule->start_datetime)}}</p>
    <p class="text-center"><strong>Job: </strong>{{$last_schedule->job}}</p>
    <p class="text-center"><strong>Task: </strong>{{$last_schedule->task}}</p>
  </div>

  <div class="row" id="breakTimeStatusContainer" 
  @if($last_schedule->breaktime->isNotEmpty() && $last_schedule->breaktime->last()->break_out)
    hidden
  @endif
  >
    <p class="text-center">Currently On Break</p>
  </div>

  <div class="row">
    <div class="col-md-12">
      <form 
        action="{{route('timeclock.update', ['schedule' => $last_schedule->id])}}" 
        method="post"
        id="updateForm"
      >
        {{method_field('PUT')}}
        {{csrf_field()}}
        <input type="hidden" name="end_date" id="end_date">
        <input type="hidden" name="end_time" id="end_time">
        <input type="hidden" name="employee" id="employee" value="{{Auth::user()->id}}">

        <div class="form-group" hidden>
            <label>Attachment</label>
            <input type="file" name="file" id="file">
        </div>

        <div class="form-group" id="breakOutBtnContainer" 
        @if($last_schedule->breaktime->isEmpty() || $last_schedule->breaktime->last()->break_out)
          hidden
        @endif
        >
            <button class="btn btn-primary btn-lg btn-block" id="breakOutBtn">
              <i class="fa fa-stop"></i>
              <span>Stop Break</span>
            </button>
        </div>

        <div id="switchContainer" 
        @if($last_schedule->breaktime->isNotEmpty() && !$last_schedule->breaktime->last()->break_out)
          hidden
        @endif
        >
          <div class="form-group">
              <button class="btn btn-primary btn-lg btn-block" id="breakInBtn">
                <i class="fa fa-pause"></i>
                <span>Start Break</span>
              </button>
          </div>
          <div class="form-group">
              <button class="btn btn-primary btn-lg btn-block" id="primaryClockoutBtn">
                <i class="fa fa-stop"></i>
                <span>Clock Out</span>
              </button>
          </div>
        </div>

        <div id="noteContainer" hidden>
           <div class="form-group">
            <label class="control-label">Notes(Optional)</label>
            @component('components.textarea',[
              'name' => 'notes', 
              'value' => old('notes', $last_schedule->notes),
              'id' => 'notes',
            ])
            @endcomponent
          </div>
          <div class="form-group">
              <button class="btn btn-primary btn-lg btn-block" id="subClockoutBtn">
                <i class="fa fa-stop"></i>
                <span>Complete Clock Out</span>
              </button>
          </div>
          <h4 class="text-center"><a href="#" id="cancelBtn">Cancel</a></h4>
        </div>
      </form>
    </div>
  </div>
  @push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
    <script>
      $(document).ready(function() {
          var SECONDS_PER_HOUR = 3600;
          var SECONDS_PER_MINUTES = 60;
          var timestamp;
          var dateIn;
          @if($last_schedule->breaktime->isNotEmpty() && !$last_schedule->breaktime->last()->break_out)
            dateIn =  moment(@json($last_schedule->breaktime->last()->break_in));
          @else
            dateIn =  moment(@json($last_schedule->start_datetime));
          @endif
          setInterval(function() {
            timestamp = moment.duration(moment().diff(dateIn));
            var hours = Math.floor(timestamp.asHours());
            var minutes = Math.floor(timestamp.asMinutes() % SECONDS_PER_MINUTES);
            var seconds = Math.floor(timestamp.asSeconds() % SECONDS_PER_MINUTES);
            $('#hours').text(hours);
            $('#minutes').text(minutes);
            $('#seconds').text(seconds);
          }, 1000);

        setInterval(function() {
          $('#end_date').val(moment().format('Y-MM-DD'));
          $('#end_time').val(moment().format('HH:mm:ss'));
        }, 1000);

        $('#primaryClockoutBtn').on('click', function(e) {
          e.preventDefault();
          $('#switchContainer').hide();
          $('#noteContainer').show();
        });

        $('#cancelBtn').on('click', function(e) {
          e.preventDefault();
          $('#noteContainer').hide();
          $('#switchContainer').show();
        });

        $('#breakInBtn').on('click', function(e) {
          e.preventDefault();
          $.ajax({
            url: @json(route('breaktime.store')),
            beforeSend: function(xhr){
              xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            },
            data: {
              break_in: moment().format('Y-MM-DD HH:mm:ss'),
              schedule_id: @json($last_schedule->id)
            },
            type: 'POST',
            success: function() {
              dateIn = moment();
              $('#workingStatusContainer').hide();
              $('#breakTimeStatusContainer').show();
              $('#switchContainer').hide();
              $('#breakOutBtnContainer').show();
            }
          })
        });

        $('#breakOutBtn').on('click', function(e) {
          e.preventDefault();

          $.ajax({
            url: @json(route('breaktime.update', ['breaktime' => 0])),
            beforeSend: function(xhr){
              xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            },
            data: {
              break_out: moment().format('Y-MM-DD HH:mm:ss'),
              schedule_id: @json($last_schedule->id)
            },
            type: 'PUT',
            success: function() {
              dateIn = moment(@json($last_schedule->start_datetime));
              $('#breakTimeStatusContainer').hide();
              $('#workingStatusContainer').show();
              $('#breakOutBtnContainer').hide();
              $('#switchContainer').show();
            }
          })
        });

        $('#subClockoutBtn').on('click', function() {
          $('#updateForm').submit();
        });
      });
    </script>
  @endpush
@endif