@php
  Carbon::setWeekStartsAt(Carbon::SUNDAY);
  Carbon::setWeekEndsAt(Carbon::SATURDAY);

  $startOfWeek = Request::get('start_of_week') 
    ? Carbon::parse(Request::get('start_of_week'))
    : Carbon::now()->startOfWeek();

    $prevWeek = clone $startOfWeek;
    $prevWeek->modify('-1 week');

    $nextWeek = clone $startOfWeek;
    $nextWeek->modify('1 week');
@endphp

@push('script')
<script>
  $(document).ready(function() {
    /** FILTER BUTTON TRIGGER */
    $('#prev').on('click', function(e) {
      e.preventDefault();
      var employee = $('#employee').val() || '';
      var start_of_week = "{{$prevWeek}}";
      location.href = "{{route('timesheet.index')}}?employee=" + employee + "&start_of_week=" + start_of_week;
    });
    
    $('#next').on('click', function(e) {
      e.preventDefault();
      var employee = $('#employee').val() || '';
      var start_of_week = "{{$nextWeek}}";
      location.href = "{{route('timesheet.index')}}?employee=" + employee + "&start_of_week=" + start_of_week;
    });
    
    $('#filter').on('click', function(e) {
      e.preventDefault();
      var employee = $('#employee').val() || '';
      var start_of_week = "{{$startOfWeek}}";
      location.href = "{{route('timesheet.index')}}?employee=" + employee + "&start_of_week=" + start_of_week;
    });

    /** BUTTONS ACTION AND TRIGGER */
    $('#byEmployeeBtn').on('click', function(e) {
      e.preventDefault();

      $(this).addClass('active');
      $('#byEmployee').show();
      $('#byJobBtn').removeClass('active');
      $('#byJob').hide();
    });

    $('#byJobBtn').on('click', function(e) {
      e.preventDefault();

      $(this).addClass('active');
      $('#byJob').show();
      $('#byEmployeeBtn').removeClass('active');
      $('#byEmployee').hide();
    });
  });
</script>
@endpush