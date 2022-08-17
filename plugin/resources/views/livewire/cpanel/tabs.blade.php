<div class="card">
<div class="card-body">

    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link @if($component=='cpanel-installations') active @endif" href="#" wire:click="loadComponent('cpanel-installations')">Installations</a></li>
        <li class="nav-item"><a class="nav-link @if($component=='cpanel-install') active @endif" href="#" wire:click="loadComponent('cpanel-install')">Install</a></li>
  </ul>

    <div class="mt-3">

        <div wire:loading>
            Loading...
        </div>

        <div>
            @if($component=='cpanel-installations')
                <livewire:cpanel-installations />
            @endif

            @if($component=='cpanel-install')
                <livewire:cpanel-install />
            @endif
        </div>

    </div>

</div>
</div>
