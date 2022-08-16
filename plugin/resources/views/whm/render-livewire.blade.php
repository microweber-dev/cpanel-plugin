@extends('whm.layout')

@section('content')
    <div id="microweber-whm-livewire" class="mt-3">
        @livewire($componentName, $componentParams)
    </div>
@endsection
