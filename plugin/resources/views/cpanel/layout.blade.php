@php
    require_once('/usr/local/cpanel/php/WHM.php');
    WHM::header('Microweber Cpanel', 1, 1);
@endphp

<livewire:styles />

@yield('content')

<livewire:scripts />

@php
    WHM::footer();
@endphp
