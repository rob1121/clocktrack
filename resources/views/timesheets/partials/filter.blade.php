<div class="row" >
    <div class="col-xs-12 col-md-5">
        <div class="form-inline form-group">
            <div class="form-group">
                <a class="btn btn-success" href="{{route('timesheet.create')}}">
                    <i class="fa fa-plus"></i>
                    <span>Add Time</span>
                </a>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="filter">Filter By:</label>
                    <select name="employee" id="employee" class="form-control">
                        <option value="" selected>All Employees</option>
                        @foreach($employeeOptions as $employee)
                            <option value="{{$employee->id}}"
                                @if(Request::get('employee') == $employee->id) selected @endif
                            >{{$employee->fullname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button id="filter" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row form-group">
    <div class="col-xs-2">
        <div class="btn-group">
            <button 
            type="submit"
            id="prev"
            class="btn btn-primary" 
            >
                <i class="fa fa-angle-left"></i>
            </button>
            <button 
            type="submit"
            id="next"
            class="btn btn-primary"
            >
                <i class="fa fa-angle-right"></i>
            </button>
        </div>
    </div>

    <div class="col-xs-5">
        <h1>
            @php 
                $start = $week[0];
                $end = $week[count($week)-1];
                $isNotNextMonth = $start->format('M') === $end->format('M');
            @endphp
            {{$start->format('M d')}}
            -
            {{$end->format($isNotNextMonth ? 'd Y' : 'M d Y')}}
        </h1>
    </div>

    <div class="col-xs-5 clearfix">
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-primary" id="byJobBtn">By Job</button>
            <button type="button" class="btn btn-primary active" id="byEmployeeBtn">By Employee</button>
        </div>
    </div>
</div>