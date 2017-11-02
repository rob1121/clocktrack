<a class="btn btn-primary btn-xs" data-toggle="modal" href='#confirmModal'>
    <i class="fa fa-trash-o"></i>
</a>

<form action="{{route("{$model}.destroy", [$model => $modelId])}}" method="post" id="deleteForm">
  {{ csrf_field()}}
  {{ method_field('DELETE') }}
</form>
@component('components.confirmation', ['id' => '#confirmModal'])
    <h1 class="text-center">Are you sure you want to delete this schedule?</h1>
@endcomponent

@push('script')
<script>
$(document).ready(function() {
  $('#confirmModal #proceed').on('click', function() {
    $('#deleteForm').submit();
  });
})
</script>
@endpush