<div>
    <h5>Microweber version</h5>
    <span>Your version is: </span> <b>{{$currentVersionOfApp}}</b> <br />
    <span>Latest version is:</span> <b>{{$latestVersionOfApp}}</b> <br />
    <span>Latest download date:</span> <b>{{$latestDownloadDateOfApp}}</b><br />
    <br />

    <h5>Available Templates ({{count($supportedTemplates)}})</h5>
    @if(!empty($supportedTemplates))
    @foreach($supportedTemplates as $template)
        {{$template['name']}} @if($template['version'])(v{{$template['version']}})@endif &nbsp;
    @endforeach
    @else
        No templates installed
    @endif

    <br />
    <br />

    <h5>Available Languages ({{count($supportedLanguages)}})</h5>
    @if(!empty($supportedLanguages))
    @foreach($supportedLanguages as $language=>$languageName)
         {{$languageName}} &nbsp;
    @endforeach
    @else
        No languages installed
    @endif

    <br />
    <br />

    <button class="btn btn-outline-success" wire:click="checkForUpdate()" wire:loading.attr="disabled">
        Check for updates
    </button>

    <div wire:loading wire:target="checkForUpdate">
        Downloading ...
    </div>

</div>
