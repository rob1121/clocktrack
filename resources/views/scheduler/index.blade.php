@extends('layouts.app')

@section('content')
      <div class="container-fluid">
        <div class="col-md-3">
        <div class="panel panel-default">
          <div class="panel-heading">
          Jobs
          </div>
          

		<!-- <div id='external-events'>
			<h4>Draggable Events</h4>
			<div class='fc-event'>My Event 1</div>
			<div class='fc-event'>My Event 2</div>
			<div class='fc-event'>My Event 3</div>
			<div class='fc-event'>My Event 4</div>
			<div class='fc-event'>My Event 5</div>
			<p>
				<input type='checkbox' id='drop-remove' />
				<label for='drop-remove'>remove after drop</label>
			</p>
		</div> -->

          <div class="list-group" id='external-events'>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="panel panel-default">
          <div class="panel-heading clearfix">
					

						<div class="btn-group" role="group">
								<button class="btn btn-sm btn-default" role="button" onclick="calendarPrev()"><strong>&lt;</strong></button>
								<button class="btn btn-sm btn-default" role="button" onclick="calendarNext()"><strong>&gt;</strong></button>
						</div>

						<span id="title"></span>

						<div class="pull-right">

								<button class="btn btn-sm btn-success" onclick="addClicked()"><i class="fa fa-plus"></i> Add Shift</button>

								<button class="btn btn-sm btn-default" onclick="showNotifyModal()">Notify Employees</button>

								<button class="btn btn-sm btn-default" data-bind="click: printSchedule"><i class="fa fa-print" aria-hidden="true"></i> Print</button>

								<div class="btn-group" role="group">
										<button class="btn btn-sm btn-default" onclick="calendarChangeView('timeline')">Day</button>
										<button class="btn btn-sm btn-default" onclick="calendarChangeView('timelineSevenDays')">Week</button>
										<button class="btn btn-sm btn-default" onclick="calendarChangeView('timelineFourteenDays')">2 Weeks</button>
										<button class="btn btn-sm btn-default" onclick="calendarChangeView('month')">Month</button>
								</div>
								<div class="btn-group" role="group">
										<button class="btn btn-sm btn-default" role="button" onclick="calendarByJob()">Job</button>
										<button class="btn btn-sm btn-default" role="button" onclick="calendarByEmployee()">Employee</button>
								</div>
						</div>
          </div>
            <div id='calendar'></div>
        </div>
        </div>
    </div>
@endsection

@push('style')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.2/fullcalendar.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@1.8.1/dist/scheduler.min.css">
	<style>
		.list-group-item.fc-event {
			padding: 15px;
			border: none;
			background: #fff;
			color: #222;
		}
	</style>
@endpush


@push('script')
	<!-- fullcalendar -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.2/fullcalendar.min.js"></script>  
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@1.8.1/dist/scheduler.min.js"></script>
	<script>
		var self = this;
		self.data = [];
		self.grouping = '';
		self.currentCalendarPage = 'timelineSevenDays';

		self.calendarPrev = function() {
			$('#calendar').fullCalendar('prev');
		};

		self.calendarNext = function() {
			$('#calendar').fullCalendar('next');
		};

		self.addClicked = function() {

		};

		self.showNotifyModal = function() {

		};

		self.fillDraggableList = function(data) {
			var ul = $('.list-group');
			ul.empty();

			data.map(function(data) {
				var eventData = JSON.stringify(data).replace(/"/g, "'");
				var list = '<a href="#" class="list-group-item fc-event"';
				list += 'data-event="'+ eventData +'"';
				list += '>';
				list += '<span style="margin-right: 5px;">';
				list += '<i class="fa fa-circle" style="color: ' + data.color + '"></i>';
				list += '</span>';
				list += '<span>';
				list += data.title;
				list += '</span>';
				list += '</div>';
				list += '</a>';

				ul.append(list);
			});

			$('#external-events .fc-event').each(function() {
				var eventData = $(this).data('event').replace(/'/g, '"');
				// extend this object with the settings you want to make

				// get the complete object data from html
				// store data so the calendar knows to render an event upon drop
				$(this).data('event', JSON.parse(eventData));
				// store data so the calendar knows to render an event upon drop
				// $(this).data('event', eventData);

				// make the event draggable using jQuery UI
				$(this).draggable({
					zIndex: 999,
					revert: true,      // will cause the event to go back to its
					revertDuration: 0  //  original position after the drag
				});
			});
		};

		self.calendarByJobCallback = function(data) {
				self.data = data;
				self.grouping = 'job';

				$.get(@json(route('api.employees')), self.fillDraggableList);
				
				$('#calendar').fullCalendar('destroy');
				self.initScheduler('Jobs', @json(route('api.jobs.all')));
		};

		self.calendarByEmployeeCallback = function(data) {
				self.data = data;
				self.grouping = 'employee';

				$.get(@json(route('api.jobs')), self.fillDraggableList);

				$('#calendar').fullCalendar('destroy');
				self.initScheduler('Employees', @json(route('api.schedules.all')));
		};

		self.calendarByJob = function() {
			if (self.grouping !== 'job')
			{
				$.get(@json(route('api.jobs')), self.calendarByJobCallback);
			}
		};

		self.calendarByEmployee = function() {
			if (self.grouping !== 'employee')
			{
				$.get(@json(route('api.employees')), self.calendarByEmployeeCallback);
			}
		};

		self.calendarChangeView = function(viewName) {
			self.currentCalendarPage = viewName;
			$('#calendar').fullCalendar('changeView', viewName);
		};

		self.dateRange = function(title) {
			$('#title').text(title);
		};

		self.calendarViewRender = function (view, element) {
			var start = moment(view.start);
			var end = moment(view.end).subtract(1, 'day');
			var endFormat = end.format('MM') === start.format('MM') ? 'D Y' : 'MMM D Y';
			self.dateRange(start.format('MMM D') + ' - ' + end.format(endFormat));
		}

		

		self.calendarEventResize = function (event, delta, revertFunc, jsEvent, ui, view) {
			var duration = moment.duration(event.end.diff(event.start));
			if(duration.asHours() > 24) revertFunc();
		}

		self.initScheduler = function(resourceTitle, jsonUrl) {
			$('#calendar').fullCalendar({
				schedulerLicenseKey: '0014662753-fcs-1482874803',
				now: moment().format('Y-MM-D'),
				viewRender: calendarViewRender,
				editable: true, // enable draggable events
				eventResize: self.calendarEventResize,
				droppable: true, // this allows things to be dropped onto the calendar
				aspectRatio: 1.8,
				scrollTime: '00:00', // undo default 6am scrollTime
				defaultView: self.currentCalendarPage,
				header: false,
				views: {
						timelineSevenDays: {
								type: 'timeline',
								duration: { days: 7 },
								slotDuration: { days: 1 },
						},
						timelineFourteenDays: {
								type: 'timeline',
								duration: { days: 14 },
								slotDuration: { days: 1 },
						}
				},
				refetchResourcesOnNavigate: true,
				resourceLabelText: resourceTitle,
				resources: self.data,
				events: jsonUrl,
				drop: function(date, jsEvent, ui, resourceId) {
					// console.log($(this).data('event'));
					// console.log('drop', date.format(), resourceId, ui, jsEvent.target);
				},
				eventReceive: function(event) { // called when a proper external event is dropped
					console.log(event);
				},
				eventDrop: function(event) { // called when an event (already on the calendar) is moved
					console.log('eventDrop', event);
				}
			});
		}

		$(function() { // document ready
			/* initialize the external events
			-----------------------------------------------------------------*/
			
			self.calendarByEmployee();
		});
	</script>
@endpush