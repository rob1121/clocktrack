<div class="btn-group" data-toggle="buttons">
			
			<label class="btn btn-success {{($value === '#20895E') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="{{$name}}" 
          id="{{$name}}" 
          autocomplete="off" 
          @if($value === '#20895E')
            checked
          @endif
          value="#20895E"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-primary {{($value === '#2579A9') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="{{$name}}" 
          id="{{$name}}" 
          autocomplete="off"
          @if($value === '#2579A9')
            checked
          @endif
          value="#2579A9"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-info {{($value === '#6B9DBB') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="{{$name}}" 
          id="{{$name}}" 
          autocomplete="off" 
          @if($value === '#6B9DBB')
            checked
          @endif
          value="#6B9DBB"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-warning {{($value === '#B6A338') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="{{$name}}" 
          id="{{$name}}" 
          autocomplete="off" 
          @if($value === '#B6A338')
            checked
          @endif
          value="#B6A338"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-danger {{($value === '#954120') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="{{$name}}" 
          id="{{$name}}" 
          autocomplete="off" 
          @if($value === '#954120')
            checked
          @endif
          value="#954120"
        >
				<span class="fa fa-check"></span>
			</label>

</div>
@push('style')
<style>
.btn span.fa {    			
	opacity: 0;				
}
.btn.active span.fa {				
	opacity: 1;				
}
</style>
@endpush