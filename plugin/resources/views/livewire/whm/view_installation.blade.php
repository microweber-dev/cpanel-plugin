<div class="mt-3">
    <p>
        <a href="{{asset('/index.cgi')}}">Back to Microweber Plugin</a>
    </p>


    <div class="card">
        <div class="card-body">


        @if($this->installedSuccess)
        <div class="alert alert-success">Application is installed successful.</div>
    @endif
    <div class="row">

        <div class="col-md-4">
            <a href="{{$this->appInstallation->url}}" target="_blank">
                <div style="background-image: url({{$this->appInstallation->getScreenshotUrl()}});background-position: top;background-size: cover;height: 400px">
                </div>
            </a>
        </div>

        <div class="col-md-8">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard"
                            type="button" role="tab" aria-controls="dashboard" aria-selected="true">Dashboard
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="modules-tab" data-bs-toggle="tab" data-bs-target="#modules" type="button"
                            role="tab" aria-controls="modules" aria-selected="false">Modules
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="templates-tab" data-bs-toggle="tab" data-bs-target="#templates" type="button"
                            role="tab" aria-controls="templates" aria-selected="false">Templates
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="database-tab" data-bs-toggle="tab" data-bs-target="#database" type="button"
                            role="tab" aria-controls="database" aria-selected="false">Database
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab" tabindex="0">

                    <div class="row">

                        <div class="col-md-12">
                            <h4 class="mt-3">
                                {{$this->appInstallation->url}}
                            </h4>
                            <a href="{{$this->appInstallation->url}}" target="_new">Visit the website</a>

                            <br />
                            <br />
                            <span>App version is: </span> <b>{{$this->appInstallation->version}}</b> <br/>
                            <span>App creation date:</span> <b>{{$this->appInstallation->created_at}}</b><br/>
                            <span>App installation type:</span>
                            <b>
                                @if($this->appInstallation->is_symlink == 1)
                                    Symlinked
                                @else
                                    Standalone
                                @endif
                            </b>
                            <br/>
                            <br/>

                            <a href="{{$this->appInstallation->url}}" target="_new"  class="btn btn-outline-primary">View Website</a>

                            @if($this->confirmLoginAsAdmin)
                                <a href="{{$this->confirmLoginAsAdmin}}" target="_new" class="btn btn-success">
                                    Confirm login</a>
                            @else
                                <button class="btn btn-outline-dark" wire:click="loginAsAdmin()">Login as Admin</button>
                                <div wire:loading wire:target="loginAsAdmin">
                                    Generating token ...
                                </div>
                            @endif

                            <button class="btn btn-outline-dark" wire:click="showReinstallOptions()">Reinstall</button>

                            @if($this->confirmUninstall)
                                <button class="btn btn-outline-danger" wire:click="uninstall()">Are you sure?</button>
                            @else
                                <button class="btn btn-outline-danger" wire:click="confirmUninstall()">Uninstall</button>
                            @endif

                            @if ($this->showReinstallOptions)
                                <div class="bg-dark p-4 mt-4">

                                    <p class="text-light">
                                        Select the type of reinstalling
                                        <br />
                                         <small class="text-light">
                                             The existing installation will be converted to selected type
                                         </small>
                                    </p>

                                    @if($reinstallMessage)
                                      <p class="text-danger">{{$reinstallMessage}}</p>
                                    @endif
                                    <select class="form-control" wire:model="reisntallType">
                                        <option value="">Select...</option>
                                        <option value="standalone">Standalone</option>
                                        <option value="symlink">Symlink</option>
                                    </select>

                                    @if($this->confirmReinstall)
                                        <button class="btn btn-outline-light mt-3" wire:click="reinstall()">Are you sure?</button>
                                    @else
                                        <button class="btn btn-outline-light mt-3" wire:click="confirmReinstall()">Reinstall</button>
                                    @endif

                                    <div wire:loading wire:target="reinstall" class="text-warning">
                                        Reinstalling ...
                                    </div>

                                </div>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <br />
                            <br />
                            <h5>Supported Languages ({{count($this->appInstallation->supported_languages)}})</h5>
                            @if(!empty($this->appInstallation->supported_languages))
                                @foreach($this->appInstallation->supported_languages as $language=>$languageName)
                                    {{$languageName}} &nbsp;
                                @endforeach
                            @else
                                No languages supported
                            @endif
                        </div>


                    </div>
                </div>
                <div class="tab-pane" id="modules" role="tabpanel" aria-labelledby="modules-tab" tabindex="0">

                    <br/>

                    <h5>Supported Modules ({{count($this->appInstallation->supported_modules)}})</h5>
                    @if(!empty($this->appInstallation->supported_modules))
                        @foreach($this->appInstallation->supported_modules as $module)
                            {{$module['name']}} (v{{$module['version']}}) &nbsp;
                        @endforeach
                    @else
                        No modules supported
                    @endif

                </div>

                <div class="tab-pane" id="templates" role="tabpanel" aria-labelledby="templates-tab" tabindex="0">

                    <br/>

                    <h5>Supported Templates ({{count($this->appInstallation->supported_templates)}})</h5>
                    @if(!empty($this->appInstallation->supported_templates))
                        @foreach($this->appInstallation->supported_templates as $template)
                            {{$template['name']}} (v{{$template['version']}}) &nbsp;
                        @endforeach
                    @else
                        No templates supported
                    @endif

                </div>
                <div class="tab-pane" id="database" role="tabpanel" aria-labelledby="database-tab" tabindex="0">
                    No details
                </div>
            </div>

        </div>

    </div>
    </div>
    </div>
</div>
