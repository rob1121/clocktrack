<select 
  id="{{$id}}"
  class="form-control break-time-select"
  multiple="multiple"
>
  <option></option>
  @foreach($options as $option)
    <option value="{{$option->value}}">{{$option->text}}</option>
  @endforeach
</select>
<input type="hidden" name="{{$name}}" id="{{$name}}">

@push('script')
<script>
  $(document).ready(function() {
    $('#{{$id}}').select2({
      placeholder: 'Select Employee',
      width: '100%',
    });

    $("#{{$id}}").on("select2:select select2:unselect", function (e) {
      $('#{{$name}}').val($('#{{$id}}').select2('val'));
    });

    @if($value)
      var val = "{{$value}}";
      
      $('#{{$id}}').val(val.split(',')).trigger('change');
    @endif
  });
</script>
@endpush
      