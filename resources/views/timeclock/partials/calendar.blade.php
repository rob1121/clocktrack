<div id='calendar'></div>

@push('style')
  <!-- fullcalendar -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.2/fullcalendar.min.css" />
  <style>
    .fc-event{
      cursor: pointer;
    }
  </style>
@endpush

@push('script')
<!-- fullcalendar -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.2/fullcalendar.min.js"></script>  
<script>
	$(document).ready(function() {
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: '{{route('api.schedules',['user'=> Auth::user()->id])}}',
      eventRender: function(event, element) {
          $(element).popover({
              title: function () {
                  return "<B>" + event.title + "</B>";
              },
              placement:'auto',
              html:true,
              trigger : 'hover',
              animation : 'false',
              content: function () {
                $('.popover').remove();
                var content = "<p>All day "+ event.schedule + " </p>";
                content += "<br/>";
                content += "<p>Job Description: "+ event.body + " </p>";
                  return content
              },
              container:'body'
          }).popover('show');
      }
    });

    $('body').on('click', function (e) {
    $('[data-toggle=popover]').each(function () {
        // hide any open popovers when the anywhere else in the body is clicked
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});
  });
</script>
@endpush