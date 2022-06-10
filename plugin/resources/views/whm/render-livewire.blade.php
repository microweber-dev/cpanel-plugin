@php
    \App\WhmLayoutApi::header('Microweber WHM Administration', 1, 1);
@endphp

<div id="microweber-whm">
<livewire:styles/>

<div id="contentContainer">

    <div class="pageTitle" id="pageTitle-whm_marketplace">
        <h1><img class="whm-app-title__image" src="{{asset('img/mw-icon.svg')}}"
                 alt="">
            <span>Microweber - Drag & drop website builder</span>
        </h1>
    </div>
    <div id="navigation">
        <div id="breadcrumbsContainer">
            <ul id="breadcrumbs_list" class="breadcrumbs ">
                <li><a href="/">
                        <span class="imageNode">Home</span></a> <span>/</span>
                </li>
                <li><a href="/">
                        <span class="imageNode">Plugins</span></a> <span>/</span>
                </li>
                <li>
                    <a href="" class="leafNode">
                        <span>Microweber</span>
                    </a>
                </li>
            </ul>
        </div>

    </div>
    <div id="navigation_affix_padding"></div>

    <div id="microweber-whm-livewire">
        <livewire:whm-tabs />
    </div>

</div>

<livewire:scripts/>

</div>

@php
    \App\WhmLayoutApi::footer();
@endphp
