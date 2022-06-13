<div>
    <h5>Microweber version</h5>
    <span>Your version is: </span> <b>{{$currentVersionOfApp}}</b> <br />
    <span>Latest version is:</span> <b>{{$latestVersionOfApp}}</b> <br />
    <span>Latest download date:</span> <b>{{$latestDownloadDateOfApp}}</b><br />
    <br />

    <h5>Available Templates ({{count($availableTemplatesOfApp)}})</h5>
    @if(!empty($availableTemplatesOfApp))
    @foreach($availableTemplatesOfApp as $template)
    {{$template['name']}} ({{$template['version']}})
    @endforeach
    @else
        No templates installed
    @endif

    <br />
    <br />

    <button class="btn btn-outline-success" wire:click="checkForUpdate()" wire:loading.attr="disabled">
        Check for updates
    </button>


    <div wire:loading wire:target="checkForUpdate()">
        Downloading ...
    </div>

</div>
