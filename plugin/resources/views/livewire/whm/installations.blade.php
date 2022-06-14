<div>

    <livewire:whm-app-installations-table />

    <br />
    <button class="btn btn-sm btn-outline-success" wire:click="scan()" wire:loading.attr="disabled">
        Scan for new installations
    </button>

    <div wire:loading wire:target="scan">
        Scanning ...
    </div>

</div>
