
  <select name="{{$accessControlId}}" class="form-control" id="{{$accessControlId}}" value="old($accessControlId)">
    <option value="allow all">Allow All</option>
    <option value="allow only">Allow Only</option>
    <option value="allow any except">Allow Any Except</option>
  </select>
  <br/>
  <select 
    id="{{$id}}"
    class="form-control break-time-select"
    multiple="multiple"
    disabled
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

    $('#{{$accessControlId}}').on('change', function() {
      if($(this).val() === 'allow all') {
        $('#{{$id}}').prop('disabled', true);
        $('#{{$id}}').val('').trigger('change');
      } else {
        $('#{{$id}}').prop('disabled', false);
      }
    });

    @if($value)
      var val = "{{$value}}";
      
      $('#{{$id}}').val(val.split(',')).trigger('change');
    @endif
  });
</script>
@endpush
      