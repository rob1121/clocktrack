<select 
  name="{{$name}}" 
  id="{{$id}}"
  class="form-control break-time-select"
  @if(isset($multiple) && $multiple)
    multiple="multiple"
  @endif
>
  <option></option>
  @foreach($options as $option)
    <option value="{{$option->value}}">{{$option->text}}</option>
  @endforeach
</select>

@push('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
<script>
  
  $(document).ready(function() {
    /** SELECT2 COMPONENTS */
      $('#{{$id}}').select2({
          placeholder: 'Select  Job',
          width: '100%',
      });
      
      $('#{{$id}}').val('{{$value}}').trigger('change');
  });
</script>
@endpush