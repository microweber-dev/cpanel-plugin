@extends('cpanel.layout')

@section('content')
    <div id="microweber-cpanel-livewire" class="mt-3">
        @livewire($componentName, $componentParams)
    </div>
@endsection
