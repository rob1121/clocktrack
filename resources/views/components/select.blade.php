<select 
  name="{{$name}}" 
  id="{{$id}}"
  class="form-control break-time-select"
>
  <option></option>
  @foreach($options as $option)
    <option value="{{$option->value}}" @if(old($name) === $option->value) selected @endif>
      {{$option->text}}
    </option>
  @endforeach
</select>