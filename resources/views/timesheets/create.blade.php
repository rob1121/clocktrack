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
          <form class="form-horizontal" method="post" action="{{route('timesheet.store')}}">
            {{csrf_field()}}
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
                        value="{{old('start_date')}}"
                      >
                    </div>

                    <div  style="padding: 0"  class="col-sm-6">
                      @component('components.select', [
                        'options' => $breaktimeOptions,
                        'name' => 'start_time',
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
                        value="{{old('end_date')}}"
                      >
                    </div>
                </div>
              </div>
            </div>

            @component('components.break_time',['breaktimeOptions' => $breaktimeOptions])
            @endcomponent
            
            <div class="form-group">
              <label class="col-sm-2 control-label">Employees</label>
              <div class="col-sm-9">
                @component('components.select2_multiple', [
                  'options' => $employeeOptions, 
                  'name' => 'employees', 
                  'id' => 'select2Employees',
                ])
                @endcomponent
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 control-label">Job</label>
              <div class="col-sm-9">
                @component('components.select2', [
                  'options' => $jobOptions, 
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
                'name' => 'task',
                'id' => 'task'
              ])
              @endcomponent
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Notes(Optional)</label>
              <div class="col-sm-9">
                <textarea class="form-control" name="notes" id="notes" cols="30" rows="5">{{old('notes')}}</textarea>
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
                <button class="btn btn-primary">
                  Add Time
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
  @include('timesheets.partials.script_create')
@endsection