<select 
  name="{{$name}}" 
  id="{{$id}}"
  class="input form-control break-time-select"
>
  <option></option>
  @foreach($options as $option)
    <option value="{{$option->value}}" @if($value === $option->value) selected @endif>
      {{$option->text}}
    </option>
  @endforeach
</select>