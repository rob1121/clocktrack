@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Schedule</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{route('shift.update',['shift'=> $schedule->id])}}">
                        {{ csrf_field() }}
                        {{method_field('PUT')}}
                        <div class="form-group{{ $errors->has('employee') ? ' has-error' : '' }}">
                            <label for="employee" class="col-md-2 control-label">Employee</label>

                            <div class="col-md-9">
                                <select name="employee" id="employee" class="form-control">
                                @foreach($employees as $employee)
                                  <option 
                                    value="{{$employee->id}}" 
                                    {{old('employee', $schedule->user_id) === $employee->id ? 'selected' : ''}}
                                  >{{$employee->title}}</option>
                                @endforeach
                                </select>

                                @if ($errors->has('employee'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('employee') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('job') ? ' has-error' : '' }}">
                            <label for="job" class="col-md-2 control-label">Job</label>

                            <div class="col-md-9">
                                <select name="job" id="job" class="form-control">
                                @foreach($jobs as $job)
                                  <option 
                                    value="{{$job->id}}" 
                                    {{old('job', $schedule->job) === $job->title ? 'selected' : ''}}
                                  >{{$job->title}}</option>
                                @endforeach
                                </select>

                                @if ($errors->has('job'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('job') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
                            <label for="notes" class="col-md-2 control-label">Notes(Optional)</label>

                            <div class="col-md-9">
                                <textarea 
                                  id="notes" 
                                  type="text" 
                                  class="form-control" 
                                  name="notes"
                                >{{ old('notes', $schedule->notes) }}</textarea>

                                @if ($errors->has('notes'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('notes') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
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
                                    
                                    <select 
                                    name="start_time" 
                                    id="start_time"
                                    class="input form-control break-time-select"
                                    >
                                    <option value="{{$schedule->start_time}}" selected>{{Carbon::parse($schedule->start_time)->format('H:i a')}}</option>
                                    @foreach($breaktimeOptions as $option)
                                        <option value="{{$option->value}}" @if(old('start_time', $schedule->start_time) === $option->value) selected @endif>
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
                                    <option value="{{$schedule->end_time}}" selected>{{Carbon::parse($schedule->end_time)->format('H:i a')}}</option>
                                    @foreach($breaktimeOptions as $option)
                                        <option value="{{$option->value}}" @if(old('end_time', $schedule->end_time) === $option->value) selected @endif>
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
                                        value="{{old('end_date', $schedule->end_date)}}"
                                    >
                                    </div>
                                </div>
                            </div>
                            </div>


                        <div class="form-group">
                            <div class="col-md-9 col-md-offset-2 text-right">
                                <button type="submit" class="btn btn-primary">
                                    Update Shift
                                </button>

                                <a href="{{route('shift.index')}}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
