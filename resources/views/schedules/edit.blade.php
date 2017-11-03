@extends('layouts.app')

@section('content')
<div class="container">
      <h1>Add Time</h1>
      
      @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif

      <div class="jumbotron">
        <div class="container">
          <form 
            class="form-horizontal" 
            method="post" 
            action="{{route('schedule.update',['schedule' => $schedule->id])}}"
          >
            {{csrf_field()}}
            {{ method_field('PUT') }}
            <div class="form-group">
            <label class="col-sm-2 control-label">When</label>
            <div class="col-sm-9">
              <div class="col-sm-5"  style="padding: 0">
                <div  style="padding: 0"  class="col-sm-6">
                      <input 
                        type="text"
                        class="form-control" 
                        name="start_date" 
                        id="start_date" 
                        placeholder="Start Date"
                        value="{{old('start_date', $schedule->start_date)}}"
                      >
                    </div>

                    <div  style="padding: 0"  class="col-sm-6">
                      @component('components.select', [
                        'options' => $breaktimeOptions,
                        'name' => 'start_time',
                        'value' => old('start_time', $schedule->start_time),
                        'id' => 'start_time',
                      ])
                      @endcomponent
                    </div>
                </div>

                  <div class="col-sm-2 text-center">
                    <label class="control-label">To</label>
                  </div>
                  
                <div class="col-sm-5"  style="padding: 0">
                    <div  style="padding: 0"  class="col-sm-6">
                      @component('components.select', [
                        'options' => $breaktimeOptions,
                        'name' => 'end_time',
                        'value' => old('end_time', $schedule->end_time),
                        'id' => 'end_time',
                      ])
                      @endcomponent
                    </div>
                    
                    <div  style="padding: 0"  class="col-sm-6">  
                      <input type="text"
                      class="form-control" 
                      name="end_date" 
                      id="end_date" 
                      placeholder="End Date"
                        value="{{old('end_date', $schedule->end_date)}}"
                      >
                    </div>
                </div>
              </div>
            </div>

            @component('components.break_time',[
              'breaktimeOptions' => $breaktimeOptions, 
              'break_in' => array_filter(old('break_in', $schedule->breaktime->pluck('break_in')->toArray())),
              'break_out' => array_filter(old('break_out', $schedule->breaktime->pluck('break_out')->toArray()))
            ])
            @endcomponent
            
            <div class="form-group">
              <label class="col-sm-2 control-label">Employees</label>
              <div class="col-sm-9">
                @component('components.select2', [
                  'options' => $employeeOptions, 
                  'name' => 'employee', 
                  'value' => old('employee', $schedule->user_id),
                  'id' => 'employee',
                ])
                @endcomponent
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 control-label">Job</label>
              <div class="col-sm-9">
                @component('components.select2', [
                  'options' => $jobOptions, 
                  'value' => old('job', $schedule->job),
                  'name' => 'job',
                  'id' => 'job'
                ])
                @endcomponent
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 control-label">Task</label>
              <div class="col-sm-9">
              @component('components.select2', [
                'options' => $taskOptions, 
                  'value' => old('task', $schedule->task),
                'name' => 'task',
                'id' => 'task'
              ])
              @endcomponent
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Notes(Optional)</label>
              <div class="col-sm-9">
                <textarea class="form-control" name="notes" id="notes" cols="30" rows="5">{{old('notes', $schedule->notes)}}</textarea>
                <small><span id="counter">500</span> characters remaining (500 maximum)</small>
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-9 col-sm-offset-2">
              <label class="control-label">Attachment</label>
                <input type="file" name="attachment" id="attachment">
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-9 col-sm-offset-2">
                <button type="submit" class="btn btn-primary">
                  Update
                </button>
                <a class="btn btn-default" href={{route('timesheet.index')}}>
                  Cancel
                </a>
              </div>
            </div>

          </form>
        </div>
      </div>
  </div>
  <!-- end of hiddne components -->

  @include('schedules.partials.script_create')
@endsection