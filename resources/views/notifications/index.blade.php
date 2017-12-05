@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h1>Notifications</h1>
          <hr>

      
          @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
           @endif

        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#admin">Admin</a></li>
          <li><a data-toggle="tab" href="#employee">Employee</a></li>
        </ul>

        <form class="form-horizontal" method="POST" action="{{route('notification.update', ['notification'=> $notif->id])}}">
                {{ csrf_field() }}
                {{method_field('PUT')}}
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

        
                        
        <div class="form-group">
                <div class=" col-md-12">
                    <button type="submit" class="btn btn-primary">
                        Update Settings
                    </button>
                </div>
            </div>
        </form>
      </div>
    </div>
</div>
@endsection
