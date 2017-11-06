@extends('layouts.app')
@section('content')
      <div class="container-fluid">
        <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
          Time clock
          </div>
          <div class="panel-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if($last_biometric && $last_biometric->active)
              @include('timeclock.partials.clock_out')
            @else
              @include('timeclock.partials.clock_in')
            @endif
          </div>
        </div>

        <div class="panel panel-default">
        <div class="panel-heading">
        My Recent Activity
        </div>
        <div class="panel-body">
          @include('timeclock.partials.log')
        </div>
      </div>


      </div>
      <div class="col-md-8">
        <div class="panel panel-default">
	        <div class="panel-body">
          <ul class="nav nav-tabs">
            <li><a href="{{route('timeclock.calendar')}}">My Schedule</a></li>
            <li class="active"><a href="{{route('timeclock.timesheet', ['user' => Auth::user()->id])}}">My Timesheet</a></li>
          </ul>
            <br>
            <div class="tab-content">
                @include('timeclock.partials.timesheet')
            </div>
          </div>
        </div>
        </div>
    </div>
@endsection