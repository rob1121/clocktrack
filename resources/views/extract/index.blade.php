@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h1>Downloads</h1>
          <hr>
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#timesheet">Timesheets</a></li>
          <li><a data-toggle="tab" href="#employeeSummary">Employee Summary</a></li>
          <li><a data-toggle="tab" href="#jobSummary">Job Summary</a></li>
          <li><a data-toggle="tab" href="#taskSummary">Task Summary</a></li>
          <li><a data-toggle="tab" href="#employeeDetails">Employee Details</a></li>
          <li><a data-toggle="tab" href="#jobDetails">Job Details</a></li>
          <li><a data-toggle="tab" href="#taskDetails">Task Details</a></li>
        </ul>

        <div class="tab-content">
          <div id="timesheet" class="tab-pane fade in active">
            <h3>Timesheets</h3>
            @include('extract.tabs.timesheets')
          </div>
          
          <div id="employeeSummary" class="tab-pane fade in">
            <h3>Employee</h3>
            employeeSummary
          </div>
          
          <div id="jobSummary" class="tab-pane fade in">
            <h3>Employee</h3>
            jobSummary
          </div>
          
          <div id="taskSummary" class="tab-pane fade in">
            <h3>Employee</h3>
            taskSummary
          </div>
          
          <div id="employeeDetails" class="tab-pane fade in">
            <h3>Employee</h3>
            employeeDetails
          </div>
          
          <div id="jobDetails" class="tab-pane fade in">
            <h3>Employee</h3>
            jobDetails
          </div>
          
          <div id="taskDetails" class="tab-pane fade in">
            <h3>Employee</h3>
            taskDetails
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
