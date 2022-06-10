<div>
    <ul class="nav nav-tabs">
        <li @if($component=='whm-installations')class="active"@endif><a href="#" wire:click="loadComponent('whm-installations')">Installations</a></li>
        <li @if($component=='whm-install')class="active"@endif><a href="#" wire:click="loadComponent('whm-install')">Install</a></li>
        <li @if($component=='whm-versions')class="active"@endif><a href="#" wire:click="loadComponent('whm-versions')">Versions</a></li>
        <li @if($component=='whm-whitelabel')class="active"@endif><a href="#" wire:click="loadComponent('whm-whitelabel')">White Label</a></li>
        <li @if($component=='whm-settings')class="active"@endif><a href="#" wire:click="loadComponent('whm-settings')">Settings</a></li>
    </ul>


    <div>
        @if($component=='whm-installations')
        <livewire:whm-installations />
        @endif

        @if($component=='whm-install')
            <livewire:whm-install />
        @endif

        @if($component=='whm-versions')
            <livewire:whm-versions />
        @endif

        @if($component=='whm-whitelabel')
            <livewire:whm-whitelabel />
        @endif

        @if($component=='whm-settings')
            <livewire:whm-settings />
        @endif
    </div>

</div>
