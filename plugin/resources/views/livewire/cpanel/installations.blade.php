<div>

    <livewire:cpanel-app-installations-table />

    <br />
    <button class="btn btn-sm btn-outline-success" wire:click="scan()" wire:loading.attr="disabled">
        Scan for new installations
    </button>

    @if($this->confirmReinstallAll)
        <button class="btn btn-sm btn-outline-danger" wire:click="reinstallAll()">Are you sure?</button>
    @else
        <button class="btn btn-sm btn-outline-danger" wire:click="confirmReinstallAll()">Reinstall all installations</button>
    @endif

    <div wire:loading wire:target="scan">
        Scanning ...
    </div>

    <div wire:loading wire:target="reinstallAll">
        Reinstalling ...
    </div>

</div>
