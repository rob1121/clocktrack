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
    });
</script>
@endpush