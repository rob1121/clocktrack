@extends('layouts.app')

@section('content')
      <div class="container-fluid">
        <div class="col-md-3">
        <div class="panel panel-default">
          <div class="panel-heading">
          Jobs
          </div>
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

								<!-- <button class="btn btn-sm btn-success" onclick="addClicked()"><i class="fa fa-plus"></i> Add Shift</button> -->

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
	
	#external-events .fc-event {
		margin: 10px 0;
		cursor: pointer;
		margin: 5px;
	}
		
	#external-events p {
		margin: 1.5em 0;
		font-size: 11px;
		color: #666;
	}
		
	#external-events p input {
		margin: 0;
		vertical-align: middle;
	}
	</style>
@endpush


@push('script')
	<!-- fullcalendar -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.2/fullcalendar.min.js"></script>  
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
				var list = '';
				
				list += '<div class="fc-event" ';
				list += 'style="background:'+ data.color +'" ';
				list += 'data-id="'+ data.id +'" ';
				list += '>';
				list += data.title;
				list += '</div>';

				ul.append(list);
			});

			$('#external-events .fc-event').each(function() {
				// var eventData = $(this).data('event').replace(/'/g, '"');
			// store data so the calendar knows to render an event upon drop
			$(this).data('event', {
				itemId: $(this).data('id'),
				color: $(this).data('color'),
				title: $.trim($(this).text()), // use the element's text as the event title
			});


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
				
				self.rerenderEvents();
		};

		self.calendarByEmployeeCallback = function(data) {
				self.data = data;
				self.grouping = 'employee';

				$.get(@json(route('api.jobs')), self.fillDraggableList);

				self.rerenderEvents();
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
			if(duration.asHours() > 24) 
			{
				revertFunc();
			} 
			else 
			{
				self.updateDb(event);
			}
		}

		self.rerenderEvents = function() {
			$('#calendar').fullCalendar('destroy');
			if(self.grouping === 'job') 
			{
				self.initScheduler('Employees', @json(route('api.jobs.all')));
			} 
			else 
			{
				self.initScheduler('Jobs', @json(route('api.schedules.all')));
			}
		}

		self.updateDb = function(event) {
			console.log(event);
			var resource = $("#calendar").fullCalendar("getResourceById", event.resourceId);
			var data = {
					start: event.start.format('Y-MM-DD HH:mm:ss'),
					end: event.end ? event.end.format('Y-MM-DD HH:mm:ss') : event.start.format('Y-MM-DD 23:59:59'),
					job: null,
					employee: null,
			};

			if (self.grouping === 'job') {
				data.employee = event.itemId;
				data.job = resource.id;
			} else {
				data.employee = resource.id;
				data.job = event.itemId;
			}
			$.ajax({
				url: '/clocktrack/public/shift/' + event.scheduleId,
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
				},
				data: data,
				dataType: 'JSON',
				type: 'PUT',
				success: self.rerenderEvents,
				error: function(error) {
					console.log('update failed', error);
				},
			});
		}

		self.insertToDb = function(event) {
			var resource = $("#calendar").fullCalendar("getResourceById", event.resourceId);
			var data = {
					start: event.start.format('Y-MM-DD HH:mm:ss'),
					end: event.end ? event.end.format('Y-MM-DD HH:mm:ss') : event.start.format('Y-MM-DD 23:59:59'),
					job: null,
					employee: null,
			};

			if (self.grouping === 'job') {
				data.employee = event.itemId;
				data.job = resource.id;
			} else {
				data.employee = resource.id;
				data.job = event.itemId;
			}

			$.ajax({
				url: @json(route('shift.store')),
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
				},
				data: data,
				dataType: 'JSON',
				type: 'POST',
				success: self.rerenderEvents,
				error: function(error) {
					console.log('insert failed', error);
				}
			});
		}

		self.deleteSchedule = function(url) {
			$.ajax({
				url: url,
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
				},
				type: 'DELETE',
				success: self.rerenderEvents,
				error: function(error) {
					console.log('deleting failed', error);
				}
			});
		}

		self.initScheduler = function(resourceTitle, jsonUrl) {
			$('#calendar').fullCalendar({
				schedulerLicenseKey: '0014662753-fcs-1482874803',
				now: moment().startOf('week').format('Y-MM-DD'),
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
				eventReceive: self.insertToDb,
				eventDrop: self.updateDb,
        eventClick: function(calEvent, jsEvent, view) {
				},
        selectable: true,
				eventRender: function(event, element) {
					var content = '';
					content += '<div class="container-fluid">';
					content += '<div class="row">';
					content += '<div class="col-xs-6">';
					content += '<a href="#" data-delurl="' + event.deleteUrl + '" class="text-center deleteButton">Delete</a>';
					content += '</div>';
					content += '<div class="col-xs-6">';
					content += '<a href="' + event.editUrl + '" class="text-center" style="white-space:nowrap">Edit Shift</a>';
					content += '</div>';
					content += '</div>';
					content += '</div>';

					var options = {
						content: content,
						container: 'body',
						html: true,
						placement: 'auto',
						title: event.title
					};
					$(element).popover(options);
				}
			});
		}

		$(document).on('show.bs.popover', function() {
			$('.popover').not(this).popover('hide');
		});

		$(function() { // document ready
			/* initialize the external events
			-----------------------------------------------------------------*/
			self.calendarByEmployee();
		});

		$(document).on('click', '.deleteButton', function(e) {
			e.preventDefault();
			self.deleteSchedule($(this).data('delurl'));
			$('.popover').popover('hide');
		});
	</script>
@endpush