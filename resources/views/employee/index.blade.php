@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              @if (session('status'))
                  @component('components.alert', ['title' => 'Schedule Added', 'icon' => 'check-circle', 'type' => 'success' ])
                  <p>{{session('status')}}</p>
                  @endcomponent
              @endif
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <h1>Employees</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <a href="{{route('employee.create')}}" class="btn btn-success">
                <span><i class="fa fa-plus"></i></span>
                <span>Add Employee</span>
              </a>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="form-group col-md-6 col-md-offset-6">
                  <form action="{{route('employee.index')}}" method="get">
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
              {{ $employees->links() }}
            </div>
          </div>  

          <div class="row">
            <div class="col-md-12">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date Created</th>
                    <th>Active</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($employees as $employee)
                    <tr>
                    <td>{{ $employee->fullname }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->phone }}</td>
                    <td>{{ Carbon::parse($employee->created_at)->format('Y-m-d') }}</td>
                    <td class="text-right">
                      <a href="{{route('employee.edit',['employee' => $employee->id])}}" class="btn btn-primary">
                          <i class="fa fa-edit"></i>
                      </a>
                      <a href="#" class="btn btn-primary deleteBtn">
                          <i class="fa fa-trash"></i>
                      </a>
                      <form method="post" action="{{route('employee.destroy', ['employee' => $employee->id])}}">
                        {{csrf_field()}}
                        {{method_field('DELETE')}}
                      </form>
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
        window.location.href = @json(route('employee.index'));
      });

      $('.deleteBtn').on('click', function() {
        $(this).siblings('form').submit();
      });

      $('.checkbox').on('change', function() {
        $.ajax({
          url: '/employee/' + $(this).data('id') + '/is-active',
          beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
          },
          data: {
            is_active: $(this).is(':checked'),
          },
          dataType: 'JSON',
          type: 'PUT',
        });
      });
    });
  </script>
@endpush