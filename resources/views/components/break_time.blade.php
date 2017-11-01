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
        'value' => old('break_in'),
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
          'value' => old('break_out'),
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
  $break_ins = old('break_in') ? array_filter(old('break_in')) : [];
  $break_outs = old('break_out') ? array_filter(old('break_out')) : [];

  $breakTimeCountentCount = max([count($break_ins), count($break_outs)]);
@endphp

@push('script')
<script>
  $(document).ready(function() {
    var breaktimeComponentCount = {{$breakTimeCountentCount}};
@for($counter = 0; 
    $counter < $breakTimeCountentCount;
    $counter++
)
  var parent = $('#addBreakBtn').closest('div');
  if(parent.attr('class') !== 'col-md-9 col-sm-offset-2') parent.addClass('col-sm-offset-2');
  var breakComponent = $('#breakTimeSelectContainer>.break-select-container').clone();
  breakComponent.find('#break_in').val("{{isset($break_ins[$counter]) ? $break_ins[$counter] : ''}}");
  breakComponent.find('#break_out').val("{{isset($break_outs[$counter]) ? $break_outs[$counter] : ''}}");
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