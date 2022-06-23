<div class="mt-5">

    <h4>{{$this->appInstallation->domain}}</h4>

    <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="true">Dashboard</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="modules-tab" data-bs-toggle="tab" data-bs-target="#modules" type="button" role="tab" aria-controls="modules" aria-selected="false">Modules</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="templates-tab" data-bs-toggle="tab" data-bs-target="#templates" type="button" role="tab" aria-controls="templates" aria-selected="false">Templates</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="database-tab" data-bs-toggle="tab" data-bs-target="#database" type="button" role="tab" aria-controls="database" aria-selected="false">Database</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab" tabindex="0">

            <br />
            <h5>Details</h5>
            <span>App version is: </span> <b>{{$appInstallationVersion}}</b> <br />
            <span>App creation date:</span> <b>{{$appInstallationCreatedAt}}</b><br />
            <br />
            <br />

            <h5>Supported Languages ({{count($appInstallationLanguages)}})</h5>
            @if(!empty($appInstallationLanguages))
                @foreach($appInstallationLanguages as $language=>$languageName)
                    {{$languageName}} &nbsp;
                @endforeach
            @else
                No languages installed
            @endif

            <hr />

            <button class="btn btn-outline-success">Update</button>
            <button class="btn btn-outline-danger">Uninstall</button>

        </div>
        <div class="tab-pane" id="modules" role="tabpanel" aria-labelledby="modules-tab" tabindex="0">

            <br />

            <h5>Installed Modules ({{count($appInstallationModules)}})</h5>
            @if(!empty($appInstallationModules))
                @foreach($appInstallationModules as $module)
                    {{$module['name']}} (v{{$module['version']}}) &nbsp;
                @endforeach
            @else
                No modules installed
            @endif


        </div>
        <div class="tab-pane" id="templates" role="tabpanel" aria-labelledby="templates-tab" tabindex="0">

            <br />

            <h5>Supported Templates ({{count($appInstallationTemplates)}})</h5>
            @if(!empty($appInstallationTemplates))
                @foreach($appInstallationTemplates as $template)
                    {{$template['name']}} (v{{$template['version']}}) &nbsp;
                @endforeach
            @else
                No templates installed
            @endif

        </div>
        <div class="tab-pane" id="database" role="tabpanel" aria-labelledby="database-tab" tabindex="0">..s.</div>
    </div>




    {{--@dump($this->appInstallation)--}}
</div>
