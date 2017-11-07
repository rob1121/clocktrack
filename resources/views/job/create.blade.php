
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
          <form class="form-horizontal" method="post" action="{{route('job.store')}}">
            {{csrf_field()}}
            <div class="form-group">
              <label class="col-sm-2 control-label">Title</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="title" id="title" placeholder="Name" value="{{old('title')}}">
              </div>
            </div>

            
            <div class="form-group">
              <label class="col-sm-2 control-label">Job Number(optional)</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="number" placeholder="Job Number" id="number" value="{{old('number')}}">
              </div>
            </div>
            

            <div class="form-group">
              <label class="col-sm-2 control-label">Description(optional)</label>
              <div class="col-sm-9">
                <textarea name="description" id="description" class="form-control" rows="10">{{old('description')}}</textarea>
              </div>
            </div>
            

            <div class="form-group">
              <div class="col-sm-9 col-sm-offset-2">
                <label class="control-label">Attachment</label>
                <input type="file" name="file" id="file" >
              </div>
            </div>

            
            <div class="form-group">
              <label class="col-sm-2 control-label">Total Budgeted Hours</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="total_hour_target" id="total_hour_target" value="{{old('total_hour_target')}}">
              </div>
            </div>

            
            <div class="form-group">
              <label class="col-sm-2 control-label">Color</label>
              <div class="col-sm-9">
              @component('components.color')
              @endcomponent
              </div>
            </div>
          </form>
        </div>
      </div>
  </div>
@endsection