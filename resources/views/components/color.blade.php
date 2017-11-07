<div class="btn-group" data-toggle="buttons">
			
			<label class="btn btn-success {{(old('color') === 'success') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="color" 
          id="color" 
          autocomplete="off" 
          @if(old('color') === 'success')
            checked
          @endif
          value="success"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-primary {{(old('color') === 'primary') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="color" 
          id="color" 
          autocomplete="off"
          @if(old('color') === 'primary')
            checked
          @endif
          value="primary"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-info {{(old('color') === 'info') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="color" 
          id="color" 
          autocomplete="off" 
          @if(old('color') === 'info')
            checked
          @endif
          value="info"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-default {{(old('color') === 'default') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="color" 
          id="color" 
          autocomplete="off" 
          @if(old('color') === 'default')
            checked
          @endif
          value="default"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-warning {{(old('color') === 'warning') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="color" 
          id="color" 
          autocomplete="off" 
          @if(old('color') === 'warning')
            checked
          @endif
          value="warning"
        >
				<span class="fa fa-check"></span>
			</label>

			<label class="btn btn-danger {{(old('color') === 'danger') ? 'active' : ''}}">
				<input 
          type="radio" 
          name="color" 
          id="color" 
          autocomplete="off" 
          @if(old('color') === 'danger')
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