@php
    \App\WhmLayoutApi::header('Microweber WHM Administration', 1, 1);
@endphp

<div id="microweber-whm">


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

<livewire:styles/>

<div id="contentContainer">

    <div class="pageTitle mt-3" id="pageTitle-whm_marketplace">
        <h5><img class="whm-app-title__image" src="{{asset('img/mw-icon.svg')}}"
                 alt="">
            <span>Microweber - Drag & drop website builder</span>
        </h5>
    </div>

    <nav aria-label="breadcrumb" class="mt-3 mb-5">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Plugins</a></li>
            <li class="breadcrumb-item active" aria-current="page">Microweber</li>
        </ol>
    </nav>

    <div id="microweber-whm-livewire" class="mt-4">
        <livewire:whm-tabs />
    </div>

</div>

<livewire:scripts/>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>


</div>

@php
    \App\WhmLayoutApi::footer();
@endphp
