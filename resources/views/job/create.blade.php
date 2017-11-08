
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
              <label class="col-sm-2 control-label">Color</label>
              <div class="col-sm-9">
                @component('components.color', ['name' => 'color', 'value' => old('color')])
                @endcomponent
              </div>
            </div>

            
            <div class="form-group">
              <label class="col-sm-2 control-label">Access Control</label>
              <div class="col-sm-9">
                @component('components.select2_access_control', [
                  'name' => 'employees[]', 
                  'id' => 'employees', 
                  'value' => old('employees'),
                  'options' => $employees
                ])
                @endcomponent
              </div>
            </div>

            
            <div class="form-group">
              <label class="col-sm-2 control-label">Task Control</label>
              <div class="col-sm-9">
                @component('components.select2_access_control', [
                  'name' => 'tasks[]', 
                  'id' => 'tasks', 
                  'value' => old('tasks'),
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
                    <input type="checkbox" value="old('track_labor_budget')" name="track_labor_budget" id="track_labor_budget">
                    Track Budgeted Hours
                  </label>
                </div>
                <input type="text" class="form-control" name="total_hour_target" id="total_hour_target" value="{{old('total_hour_target')}}">
              </div>
            </div>



            <div class="form-group">
              <label class="col-sm-2 control-label">Trask hours remaining</label>
              <div class="col-sm-9">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="old('track_when_budget_hits')" name="track_when_budget_hits" id="track_when_budget_hits">
                      Track Budgeted Hours
                    </label>
                </div>
                <input type="text" class="form-control" name="hours_remaining" id="hours_remaining" value="{{old('hours_remaining')}}">
              </div>
            </div>
            

            <div class="form-group">
              <label class="col-sm-2 control-label">Job Address</label>
              <div class="col-sm-9">
                <div class="row">
                  <div class="col-sm-12">
                    <label>Address</label>
                    <textarea name="address" id="address" class="form-control" rows="10">{{old('address')}}</textarea>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>City</label>
                          <input type="text" class="form-control" name="city" placeholder="City" id="city" value="{{old('city')}}">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>State/Province</label>
                          <input type="text" class="form-control" name="state" placeholder="State" id="state" value="{{old('state')}}">
                        </div>
                      </div>
                    </div>
                  </div>
                  </div>
                  
                <div class="row">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>Postal Code</label>
                          <input type="text" class="form-control" name="postal_code" placeholder="postal_code" id="postal_code" value="{{old('postal_code')}}">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>Country</label>
                          <input type="text" class="form-control" name="country" placeholder="country" id="country" value="{{old('country')}}">
                        </div>
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
                    <input type="checkbox" value="old('remind_clockin')" name="remind_clockin" id="remind_clockin">
                    Remind Employee to clock in
                  </label>
                </div>
                
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="old('remind_clockout')" name="remind_clockout" id="remind_clockout">
                    Remind Employee to clock out
                  </label>
                </div>
              </div>
            </div>

                  
            <div class="row">
              <div class="col-sm-12 text-left">
                  <button type="submit" class="btn btn-primary">Add Job</button>
                  <a href="{{route('job.index')}}" class="btn btn-primary">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
  </div>
@endsection