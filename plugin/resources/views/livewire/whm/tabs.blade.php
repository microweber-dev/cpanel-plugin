<div>

    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link @if($component=='whm-installations') active @endif" href="#" wire:click="loadComponent('whm-installations')">Installations</a></li>
        <li class="nav-item"><a class="nav-link @if($component=='whm-install') active @endif" href="#" wire:click="loadComponent('whm-install')">Install</a></li>
        <li class="nav-item"><a class="nav-link @if($component=='whm-versions') active @endif" href="#" wire:click="loadComponent('whm-versions')">Versions</a></li>
        <li class="nav-item"><a class="nav-link @if($component=='whm-whitelabel') active @endif" href="#" wire:click="loadComponent('whm-whitelabel')">White Label</a></li>
        <li class="nav-item"><a class="nav-link @if($component=='whm-settings') active @endif" href="#" wire:click="loadComponent('whm-settings')">Settings</a></li>
    </ul>

    <div class="mt-3">

        <div wire:loading>
            Loading...
        </div>

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

</div>
