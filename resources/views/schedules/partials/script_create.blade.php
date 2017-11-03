@push('script')
<script>
  $(document).ready(function() {
      
      /** DATEPICKER COMPONENT */
      $('#start_date').datepicker({
        todayHighlight: true,
        autoclose: true
      });
      $('#end_date').datepicker({
        todayHighlight: true,
        autoclose: true
      });

      /** BUTTONS ACTION AND TRIGGER */

      /**TEXTAREA KEYUP LISTENER */
      $('#notes').on('keyup', function() {
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