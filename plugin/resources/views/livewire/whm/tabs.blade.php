<div>
    <ul class="nav nav-tabs">
        <li @if($component=='installations')class="active"@endif><a href="#" wire:click="loadComponent('installations')">Installations</a></li>
        <li @if($component=='install')class="active"@endif><a href="#" wire:click="loadComponent('install')">Install</a></li>
        <li @if($component=='versions')class="active"@endif><a href="#" wire:click="loadComponent('versions')">Versions</a></li>
        <li @if($component=='whitelabel')class="active"@endif><a href="#" wire:click="loadComponent('whitelabel')">White Label</a></li>
        <li @if($component=='settings')class="active"@endif><a href="#" wire:click="loadComponent('settings')">Settings</a></li>
    </ul>

    {{$component}}

</div>
