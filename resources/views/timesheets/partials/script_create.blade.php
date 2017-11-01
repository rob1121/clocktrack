
@push('style')
  <!-- select2 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
  <!-- datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" />
@endpush

@push('script')
  <!-- select2 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
  <!-- datepicker -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.js"></script>  
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