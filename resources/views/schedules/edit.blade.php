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
            action="{{route('schedule.update',['schedule' => $biometric->id])}}"
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
                        value="{{old('start_date', $biometric->start_date)}}"
                      >
                    </div>

                    <div  style="padding: 0"  class="col-sm-6">
                    
                    <select 
                      name="end_time" 
                      id="end_time"
                      class="input form-control break-time-select"
                    >
                      @foreach($breaktimeOptions as $option)
                        <option value="{{$biometric->start_time}}" selected>{{$biometric->start_time}}</option>
                        <option value="{{$option->value}}" @if(old('start_time', $biometric->start_time) === $option->value) selected @endif>
                          {{$option->text}}
                        </option>
                      @endforeach
                    </select>
                    </div>
                </div>

                  <div class="col-sm-2 text-center">
                    <label class="control-label">To</label>
                  </div>
                  
                <div class="col-sm-5"  style="padding: 0">
                    <div  style="padding: 0"  class="col-sm-6">
                    <select 
                      name="end_time" 
                      id="end_time"
                      class="input form-control break-time-select"
                    >
                      @foreach($breaktimeOptions as $option)
                        <option value="{{$biometric->end_time}}" selected>{{$biometric->end_time}}</option>
                        <option value="{{$option->value}}" @if(old('end_time', $biometric->end_time) === $option->value) selected @endif>
                          {{$option->text}}
                        </option>
                      @endforeach
                    </select>
                    </div>
                    
                    <div  style="padding: 0"  class="col-sm-6">  
                      <input type="text"
                      class="form-control" 
                      name="end_date" 
                      id="end_date" 
                      placeholder="End Date"
                        value="{{old('end_date', $biometric->end_date)}}"
                      >
                    </div>
                </div>
              </div>
            </div>
            @php
              $breakIns = [];
              $breakOut = [];
              if($biometric->breaktime->isNotempty()) {
                $breakIns = $biometric->breaktime ? $biometric->breaktime->pluck('break_in')->toArray():[]; 
                $breakIns = collect($breakIns);
                $breakIns = $breakIns->map(function($breakIn) {
                  return (object)[
                    'value' => Carbon::parse($breakIn)->format('H:i:s'),
                    'text' => Carbon::parse($breakIn)->format('h:i a'),
                  ];
                });
              }
              
              $breaktimeOptions = collect($breaktimeOptions)->merge($breakIns->toArray());

              if($biometric->breaktime->isNotempty()) {
                $breakOuts = $biometric->breaktime ? $biometric->breaktime->pluck('break_out')->toArray():[]; 
                $breakOuts = collect($breakOuts)->map(function($breakOut) {
                  return (object)[
                    'value' => Carbon::parse($breakOut)->format('H:i:s'),
                    'text' => Carbon::parse($breakOut)->format('h:i a'),
                  ];
                });

                $breaktimeOptions = collect($breaktimeOptions)->merge($breakOuts->toArray());
              }
            @endphp

            @component('components.break_time',[
              'breaktimeOptions' => $breaktimeOptions, 
              'break_in' => array_filter(old('break_in', $biometric->breaktime ? $biometric->breaktime->pluck('break_out')->toArray():[])),
              'break_out' => array_filter(old('break_out', $biometric->breaktime ? $biometric->breaktime->pluck('break_in')->toArray():[]))
            ])
            @endcomponent
            
            <div class="form-group">
              <label class="col-sm-2 control-label">Employees</label>
              <div class="col-sm-9">
                @component('components.select2', [
                  'options' => $employeeOptions, 
                  'name' => 'employee', 
                  'value' => old('employee', $biometric->user_id),
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
                  'value' => old('job', $biometric->job),
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
                  'value' => old('task', $biometric->task),
                'name' => 'task',
                'id' => 'task'
              ])
              @endcomponent
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Notes(Optional)</label>
              <div class="col-sm-9">
                <textarea class="form-control" name="notes" id="notes" cols="30" rows="5">{{old('notes', $biometric->notes)}}</textarea>
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