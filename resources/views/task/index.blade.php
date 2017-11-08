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
              <h1>Tasks</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <a href="{{route('task.create')}}" class="btn btn-success">
                <span><i class="fa fa-plus"></i></span>
                <span>Add Task</span>
              </a>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="form-group col-md-6 col-md-offset-6">
                  <form action="{{route('task.index')}}" method="get">
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
              {{ $tasks->links() }}
            </div>
          </div>  

          <div class="row">
            <div class="col-md-12">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Date Created</th>
                    <th>Active</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($tasks as $task)
                    <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->code }}</td>
                    <td>{{ Carbon::parse($task->created_at)->format('Y-m-d') }}</td>
                    <td>
                        <input 
                          type="checkbox" 
                          checked="{{$task->active}}" 
                          class="checkbox" 
                          data-id="{{$task->id}}"
                        >
                    </td>
                    <td class="text-right">
                      <a href="{{route('task.edit',['task' => $task->id])}}" class="btn btn-primary">
                          <i class="fa fa-edit"></i>
                      </a>
                      <a href="#" class="btn btn-primary deleteBtn">
                          <i class="fa fa-trash"></i>
                      </a>
                      <form method="post" action="{{route('task.destroy', ['task' => $task->id])}}">
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
        window.location.href = @json(route('task.index'));
      });

      $('.deleteBtn').on('click', function() {
        $(this).siblings('form').submit();
      });

      $('.checkbox').on('change', function() {
        $.ajax({
          url: '/task/' + $(this).data('id') + '/is-active',
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