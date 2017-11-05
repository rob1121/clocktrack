
  <textarea class="form-control" name="{{$name}}" id="{{$id}}" cols="30" rows="5">{{$value}}</textarea>
  <small><span id="counter">500</span> characters remaining (500 maximum)</small>

@push('script')
  <script>
    $(document).ready(function() {
      /**TEXTAREA KEYUP LISTENER */
      $('#{{$id}}').on('keyup', function() {
        var text = $(this).val();
        var charCount = text.length;
        var counter = $('#counter');
        var counterContainer = counter.parent();

        counter.text(500 - charCount);

        if (charCount >= 500) {
          counterContainer.addClass('text-danger');
          $(this).val(text.substr(0,2));
        } else {
          counterContainer.removeClass('text-danger');
        };
      });
    });
  </script>
@endpush