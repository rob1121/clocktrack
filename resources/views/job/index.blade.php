@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        @if (session('status'))
            @component('components.alert', ['title' => 'Schedule Added', 'icon' => 'check-circle', 'type' => 'success' ])
            <p>{{session('status')}}</p>
            @endcomponent
        @endif
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h1>Jobs</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <a href="{{route('job.create')}}" class="btn btn-success">
                <span><i class="fa fa-plus"></i></span>
                <span>Add Job</span>
              </a>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="form-group col-md-6 col-md-offset-6">
                  <form action="{{route('job.index')}}" method="get">
                    <div class="input-group">
                        <input name="q" type="text" class="form-control" placeholder="Search for..." value="{{Request::get('q')}}">
                        <span class="input-group-btn">
                          <button type="submit" class="btn btn-primary" type="button">Search</button>
                          <button class="btn btn-primary" type="button" id="clearBtn">Clear</button>
                        </span>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>  
          
          <div class="row">
            <div class="col-md-12 text-right">
              {{ $jobs->links() }}
            </div>
          </div>  

          <div class="row">
            <div class="col-md-12">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Number</th>
                    <th>Hour Budgeted</th>
                    <th>Houts Worked</th>
                    <th>Date Created</th>
                    <th>Active</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($jobs as $job)
                    <tr>
                    <td>{{ $job->title }}</td>
                    <td>{{ $job->number }}</td>
                    <td>{{ $job->total_hour_target }}</td>
                    <td>{{ $job->total_hour_target }}</td>
                    <td>{{ $job->created_at }}</td>
                    <td>
                        <input type="checkbox" checked="{{$job->active}}">
                    </td>
                    <td class="text-right">
                      <button class="btn btn-primary">
                          <i class="fa fa-edit"></i>
                      </button>
                      <button class="btn btn-primary">
                          <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div> 
    </div>
@endsection

@push('script')
<script>
  $(document).ready(function() {
    $('#clearBtn').on('click', function() {
      window.location.href = @json(route('job.index'));
    });
  });
</script>
@endpush