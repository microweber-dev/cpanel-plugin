@php
    \App\WhmLayoutApi::header('Microweber WHM Administration', 1, 1);
@endphp

<div id="microweber-whm">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

    <livewire:styles />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-weight:300;
            font-size:15px;
            background: #f9fbfd;
        }
        .h5, h5 {
            font-weight: 300;
        }
        .nav-tabs > li {
            margin:0px;
        }
        .nav-tabs > li > a {
            font-weight:300;
            color: #2d82c4;
            border:0px;
            margin:0px;
        }
        .nav-tabs .nav-link {
            border:0px;
        }
        .nav-tabs > li > a:hover {
            color: #2d82c4;
        }
        a:focus {
            outline:0px;
        }
        a {
            text-decoration:none;
            color: #2d82c4;
        }
        .card {
            border:0px;
            box-shadow: rgb(234, 234, 234) 0px 0px 18px;
        }
        .nav-tabs {
            --bs-nav-tabs-border-width: 3px;
            border-bottom: 3px solid #efefef;
        }
        .nav-tabs .nav-item .nav-link {
            font-weight:500;
            color:#000000;
            border-bottom: 3px solid #efefef;
        }
        .nav-tabs .nav-link:focus, .nav-tabs .nav-link:hover {
            border-bottom: 3px solid #2d82c4;
            color:#2d82c4;
            background: none;
        }
        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
            background:none !important;
            border-top:0px !important;
            border-right:0px !important;
            border-left:0px !important;
            border-bottom: 3px solid #2d82c4;
            color:#2d82c4;
        }
        .btn {
            border-radius: 16px;
        }
    </style>

    <div id="contentContainer">

        <div class="pageTitle mt-5" id="pageTitle-whm_marketplace">
            <h5><img class="whm-app-title__image" src="{{asset('img/mw-icon.svg')}}" style="max-width:50px" alt="">
                <span>Microweber - Drag & Drop CMS</span>
            </h5>
        </div>

        <nav aria-label="breadcrumb" class="mt-3 mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">Plugins</li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{asset('/index.cgi')}}">Microweber Plugin</a></li>
            </ol>
        </nav>


        @yield('content')


    </div>

    <livewire:scripts/>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>


</div>

@php
    \App\WhmLayoutApi::footer();
@endphp
