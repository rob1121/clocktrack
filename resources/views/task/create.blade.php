
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
          <form class="form-horizontal" method="post" action="{{route('task.store')}}">
            {{csrf_field()}}
            <div class="form-group">
              <label class="col-sm-2 control-label">Title</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="title" id="title" placeholder="Name" value="{{old('title')}}">
              </div>
            </div>

            
            <div class="form-group">
              <label class="col-sm-2 control-label">Task Code(optional)</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="code" id="code" placeholder="Name" value="{{old('code')}}">
              </div>
            </div>

            
            <div class="form-group">
              <label class="col-sm-2 control-label">Access Control</label>
              <div class="col-sm-9">
                @component('components.select2_access_control', [
                  'name' => 'employees', 
                  'id' => 'employees', 
                  'accessControlId' => 'taskAccessControlId',
                  'value' => old('employees'),
                  'options' => $employees
                ])
                @endcomponent
              </div>
            </div>
              
            <div class="form-group text-right">
              <div class="col-sm-11">
                <button class="btn btn-primary">Add Task</button>
                <a href="{{route('task.index')}}" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
  </div>
@endsection