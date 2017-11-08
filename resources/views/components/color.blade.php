<div class="btn-group" data-toggle="buttons">
			
			<label class="btn btn-success {{($value === 'success') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="{{$name}}" 
          id="{{$name}}" 
          autocomplete="off" 
          @if($value === 'success')
            checked
          @endif
          value="success"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-primary {{($value === 'primary') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="{{$name}}" 
          id="{{$name}}" 
          autocomplete="off"
          @if($value === 'primary')
            checked
          @endif
          value="primary"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-info {{($value === 'info') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="{{$name}}" 
          id="{{$name}}" 
          autocomplete="off" 
          @if($value === 'info')
            checked
          @endif
          value="info"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-default {{($value === 'default') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="{{$name}}" 
          id="{{$name}}" 
          autocomplete="off" 
          @if($value === 'default')
            checked
          @endif
          value="default"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-warning {{($value === 'warning') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="{{$name}}" 
          id="{{$name}}" 
          autocomplete="off" 
          @if($value === 'warning')
            checked
          @endif
          value="warning"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-danger {{($value === 'danger') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="{{$name}}" 
          id="{{$name}}" 
          autocomplete="off" 
          @if($value === 'danger')
            checked
          @endif
          value="danger"
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