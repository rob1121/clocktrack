
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
          <form class="form-horizontal" method="post" action="{{route('job.update',['job' => $job->id])}}" enctype="multipart/form-data">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <div class="form-group">
              <label class="col-sm-2 control-label">Title</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="title" id="title" placeholder="Name" value="{{old('title', $job->title)}}">
              </div>
            </div>

            
            <div class="form-group">
              <label class="col-sm-2 control-label">Job Number(optional)</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="number" placeholder="Job Number" id="number" value="{{old('number', $job->number)}}">
              </div>
            </div>
            

            <div class="form-group">
              <label class="col-sm-2 control-label">Description(optional)</label>
              <div class="col-sm-9">
                <textarea name="description" id="description" class="form-control" rows="10">{{old('description', $job->description)}}</textarea>
              </div>
            </div>
            

            <div class="form-group">
              <div class="col-sm-9 col-sm-offset-2">
                <label class="control-label">Attachment</label>
                <input type="file" name="file" id="file" >
              </div>
            </div>

            
            <div class="form-group">
              <label class="col-sm-2 control-label">Color</label>
              <div class="col-sm-9">
                @component('components.color', ['name' => 'color', 'value' => old('color', $job->color)])
                @endcomponent
              </div>
            </div>

            
            <div class="form-group">
              <label class="col-sm-2 control-label">Access Control</label>
              <div class="col-sm-9">
                @component('components.select2_multiple', [
                  'name' => 'employees', 
                  'id' => 'select2_employees', 
                  'value' => old('employees', $selectedEmployees),
                  'options' => $employees
                ])
                @endcomponent
              </div>
            </div>

            
            <div class="form-group">
              <label class="col-sm-2 control-label">Task Control</label>
              <div class="col-sm-9">
                @component('components.select2_multiple', [
                  'name' => 'tasks', 
                  'id' => 'select2_tasks', 
                  'value' => old('tasks', $selectedTasks),
                  'options' => $tasks
                ])
                @endcomponent
              </div>
            </div>



            <div class="form-group">
              <label class="col-sm-2 control-label">Labor budget</label>
              <div class="col-sm-9">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="1" name="track_labor_budget" id="track_labor_budget" {{old('track_labor_budget', $job->track_labor_budget) ? 'checked' : ''}}>
                    Track Budgeted Hours
                  </label>
                </div>
                <input type="text" class="form-control" name="total_hour_target" id="total_hour_target" value="{{old('total_hour_target', $job->total_hour_target)}}" disabled>
              </div>
            </div>



            <div class="form-group">
              <label class="col-sm-2 control-label">Trask hours remaining</label>
              <div class="col-sm-9">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="1" name="track_when_budget_hits" id="track_when_budget_hits" {{old('track_when_budget_hits', $job->track_when_budget_hits) ? 'checked' : ''}}>
                      Track Remaining Hours
                    </label>
                </div>
                <input type="text" class="form-control" name="hours_remaining" id="hours_remaining" value="{{old('hours_remaining', $job->hours_remaining)}}" disabled>
              </div>
            </div>
            

            <div class="form-group">
              <label class="col-sm-2 control-label">Job Address</label>
              <div class="col-sm-9">
                <div class="row">
                  <div class="col-sm-12">
                    <label>Address</label>
                    <textarea name="address" id="address" class="form-control" rows="10">{{old('address', $job->address)}}</textarea>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-6">
                          <label>City</label>
                          <input type="text" class="form-control" name="city" placeholder="City" id="city" value="{{old('city', $job->city)}}">
                        </div>
                      <div class="col-sm-6">
                          <label>State/Province</label>
                          <input type="text" class="form-control" name="state" placeholder="State" id="state" value="{{old('state', $job->state)}}">
                        </div>
                    </div>
                  </div>
                  </div>
                  
                <div class="row">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-6">
                          <label>Postal Code</label>
                          <input type="text" class="form-control" name="postal_code" placeholder="postal_code" id="postal_code" value="{{old('postal_code', $job->postal_code)}}">
                        </div>
                      <div class="col-sm-6">
                          <label>Country</label>
                          <input type="text" class="form-control" name="country" placeholder="country" id="country" value="{{old('country', $job->country)}}">
                        </div>
                    </div>
                  </div>
                  </div>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-sm-9 col-sm-offset-2">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="1" name="remind_clockin" id="remind_clockin" {{old('remind_clockin', $job->remind_clockin) ? 'checked' : ''}}>
                    Remind Employee to clock in
                  </label>
                </div>
                
                <div class="checkbox">
                  <label>
                    <input type="checkbox"  name="remind_clockout" id="remind_clockout" value="1" {{old('remind_clockout', $job->remind_clockout) ? 'checked' : ''}}>
                    Remind Employee to clock out
                  </label>
                </div>
              </div>
            </div>

                  
            <div class="row">
              <div class="col-sm-11 text-right">
                  <button type="submit" class="btn btn-primary">Update Job</button>
                  <a href="{{route('job.index')}}" class="btn btn-primary">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
  </div>
@endsection

@push('script')
<script>
  $('#track_labor_budget').on('change',function() {
    $('#total_hour_target').prop('disabled', !$(this).is(':checked'));
  });

  $('#track_when_budget_hits').on('change',function() {
    $('#hours_remaining').prop('disabled', !$(this).is(':checked'));
  });
  
  $(document).ready(function() {
    $('#total_hour_target').prop('disabled', !$('#track_labor_budget').is(':checked'));
    $('#hours_remaining').prop('disabled', !$('#track_when_budget_hits').is(':checked'));
  })
</script>
@endpush