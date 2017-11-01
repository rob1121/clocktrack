@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        @if (session('status'))
            @component('components.alert', ['title' => 'Schedule Added', 'icon' => 'check-circle', 'type' => 'success' ])
            <p>{{session('status')}}</p>
            @endcomponent
        @endif
        @include('timesheets.partials.filter')
        @include('timesheets.partials.by_employee')
        @include('timesheets.partials.by_job')
        @include('timesheets.partials.script_index')
    </div>
@endsection
