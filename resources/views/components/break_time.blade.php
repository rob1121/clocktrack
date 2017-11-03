<div class="form-group">
  <label class="col-sm-2 control-label">Lunch</label>
  <div class="col-sm-9">
    <div id="breakContainer">
    </div>
  </div>
  <div class="col-sm-9">
    <button class="btn btn-success" id="addBreakBtn">
      <i class="fa fa-plus"></i>
      <span>Add Break</span>
    </button>
  </div>
</div>

<div id="breakTimeSelectContainer" style="display: none">
  <div class="break-select-container">
    <div class="col-sm-5"  style="padding: 0">
      <div class="col-sm-6 col-sm-offset-6"  style="padding: 0;margin-bottom: 10px">
      @component('components.select', [
        'value' => '',
        'options' => $breaktimeOptions, 
        'name' => 'break_in[]',
        'id' => 'break_in',
      ])
      @endcomponent
      </div>
    </div>
    <div class="col-sm-5 col-sm-offset-2"  style="padding: 0;margin-bottom: 10px">
      <div class="col-sm-6"  style="padding: 0">
        @component('components.select', [
          'value' => '',
          'options' => $breaktimeOptions, 
          'name' => 'break_out[]',
          'id' => 'break_out',
        ])
        @endcomponent
      </div>
      <div class="col-sm-6">
        <a class="btn btn-xs btn-danger removeBtn" role="button">
          <i class="fa fa-minus"></i>
        </a>
      </div>
    </div>
  </div>
</div>

@php
  $breakTimeCountentCount = max([count($break_in), count($break_out)]);
@endphp

@push('script')
<script>
  $(document).ready(function() {
    console.log(@json($breakTimeCountentCount), @json($break_in));
    var breaktimeComponentCount = {{$breakTimeCountentCount}};
@for($counter = 0; 
    $counter < $breakTimeCountentCount;
    $counter++
)
  var parent = $('#addBreakBtn').closest('div');
  if(parent.attr('class') !== 'col-md-9 col-sm-offset-2') parent.addClass('col-sm-offset-2');
  var breakComponent = $('#breakTimeSelectContainer>.break-select-container').clone();
  breakComponent.find('#break_in').val("{{Carbon::parse($break_in[$counter])->format('H:i:s')}}");
  breakComponent.find('#break_out').val("{{Carbon::parse($break_out[$counter])->format('H:i:s')}}");
  var comp = breakComponent.appendTo('#breakContainer');
@endfor

      $('#addBreakBtn').on('click', function(e) {
        var parent = $(this).closest('div');

        e.preventDefault();
        if(parent.attr('class') !== 'col-md-9 col-sm-offset-2') parent.addClass('col-sm-offset-2');
        var breakComponent = $('#breakTimeSelectContainer>.break-select-container').clone();
        breakComponent.appendTo('#breakContainer');
        breaktimeComponentCount++;
      });

      $(document).delegate('a.removeBtn', 'click', function(e) {
        e.preventDefault();

        $(this).closest('.break-select-container').remove();
        breaktimeComponentCount--;
        if(breaktimeComponentCount === 0) $('#addBreakBtn').closest('div').removeClass('col-sm-offset-2');
      });
  });
</script>
@endpush