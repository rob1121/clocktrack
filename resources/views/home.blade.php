@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <div class="form-inline form-group">
                <div class="form-group">
                    <button class="btn btn-success">
                        <i class="fa fa-plus"></i>
                        <span>Add Time</span>
                    </button>
                </div>
                <div class="form-group">
                    <label for="filter">Filter By:</label>
                    <select name="filter" id="filter" class="form-control">
                        <option value="" selected disabled>All Employees</option>
                        <option value="">{{Auth::user()->fullName()}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Filter</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-xs-2">
            <div class="btn-group">
                <button class="btn btn-primary"><i class="fa fa-angle-left"></i></button>
                <button class="btn btn-primary"><i class="fa fa-angle-right"></i></button>
            </div>
        </div>

        <div class="col-xs-5">
            <h1>
                {{Carbon::setWeekStartsAt(Carbon::SUNDAY)}}
                {{Carbon::now()->startOfWeek()->format('M d')}}
                -
                @php 
                    $isNotNextMonth = Carbon::now()->endOfWeek()->format('M') === Carbon::now()->startOfWeek()->format('M');
                @endphp
                {{Carbon::now()->endOfWeek()->format($isNotNextMonth ? 'd Y' : 'M d Y')}}
            </h1>
        </div>

        <div class="col-xs-5 clearfix">
            <div class="btn-group pull-right">
                <button type="button" class="btn btn-primary">By Job</button>
                <button type="button" class="btn btn-primary active">By Employee</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th></th>
                        @for($date=Carbon::now()->startOfWeek();
                                $date->diffInDays(Carbon::now()->endOfWeek())>0;
                                $date->addDay())
                                <th>{{$date->format('D m/d')}}</th>
                        @endfor
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{Auth::user()->fullName()}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>0 hh:mm</td>
                    </tr>
                </tbody>
            </table>
        </div>
</div>
@endsection
