<div class="alert alert-{{$type}}">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong>
        @if(isset($icon))
          <i class="fa fa-{{$icon}}"></i>
        @endif
        @if(isset($title))
          <span>{{$title}}</span>
        @endif
      </strong>
      {{$slot}}
    </div>