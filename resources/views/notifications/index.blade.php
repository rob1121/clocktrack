@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h1>Notifications</h1>
          <hr>
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#admin">Admin</a></li>
          <li><a data-toggle="tab" href="#employee">Employee</a></li>
        </ul>
        <div class="tab-content">
          <div id="admin" class="tab-pane fade in active">
            <h3>Admin</h3>
            @include('notifications.admin_edit')
          </div>
          
          <div id="employee" class="tab-pane fade in">
            <h3>Employee</h3>
            @include('notifications.employee_edit')
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
