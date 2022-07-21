<div class="row justify-content-center my-5">
    <div class="col-sm-12 col-md-6 col-lg-6 my-4">

        <center>
            <h5><b>Choose installation options</b></h5>
            <p>
                Random values will be generated if fields are left blank.
            </p>
        </center>

        <div class="input-group mb-3">
            <label class="input-group-text">Domain</label>
            <select class="form-select" id="domain" wire:model="installationDomainName" style="height: 38px;">
                <option>Select domain...</option>
                @foreach($domains as $domain)
                    <option value="{{$domain['domain']}}">{{$domain['domain']}}</option>
                @endforeach
            </select>
            <span class="input-group-text" style="height: 38px;">/</span>
            <input type="text" class="form-control" wire:model="installationDomainPath" style="height: 38px;">
        </div>

        <br />
        <center> <h5><b>Installation Details</b></h5></center>
        <div class="mb-3 mt-4">
            <label class="form-label" for="installationLanguage">Installation language</label>
            <select class="form-select" wire:model.defer="installationLanguage" id="installationLanguage">
                <option>Select language...</option>
                @foreach($supportedLanguages as $language=>$languageName)
                    <option value="{{$language}}">{{$languageName}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label" for="installationTemplate">Installation template</label>
            <select class="form-select" wire:model.defer="installationTemplate" id="installationTemplate">
                <option>Select template...</option>
                @foreach($supportedTemplates as $template)
                    <option value="{{$template['targetDir']}}">{{$template['name']}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label d-block">Installation type</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" id="default" type="radio" wire:model.defer="installationType" value="default" name="installation_type"  />
                <label class="form-check-label" for="default">Default</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" id="symLinkedSavesABigAmountOfDiskSpace" type="radio" wire:model.defer="installationType" value="symlinked" name="installation_type"  />
                <label class="form-check-label" for="symLinkedSavesABigAmountOfDiskSpace">Sym-Linked <small>(saves a big amount of disk space)</small></label>
            </div>
            <div class="invalid-feedback" data-sb-feedback="defaultInstallationType:required">One option is required.</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="databaseDriver">Database Driver</label>
            <select class="form-select" id="databaseDriver" name="installation_database_driver" wire:model.defer="installationDatabaseDriver">
                <option value="sqlite">SQLite</option>
                <option value="mysql">MySql</option>
            </select>
        </div>

        <br />
        <center> <h5><b>Admin Login Details</b></h5></center>
        <br />

        <div class="mb-3">
            <label class="form-label" for="adminEmail">Admin Email</label>
            <input class="form-control" id="adminEmail" type="email" wire:model="installationAdminEmail" placeholder="Admin Email" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="adminUsername">Admin Username</label>
            <input class="form-control" id="adminUsername" type="text" wire:model="installationAdminUsername" placeholder="Admin Username" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="adminPassword">Admin Password</label>
            <input class="form-control" id="adminPassword" type="password" wire:model="installationAdminPassword" placeholder="Admin Password" />
        </div>

        <button class="btn btn-outline-success btn-block mt-4" wire:click="startInstall()" type="button">Install</button>

        <div wire:loading wire:target="startInstall">
            Installing ... <div id="js-installation-log"></div>
        </div>
        
        <script type="text/javascript">
            function readInstallationLog()
            {
                var request = new XMLHttpRequest();
                request.open('GET', '{{asset($logFilename)}}', true);
                request.send(null);
                request.onreadystatechange = function () {
                    if (request.readyState === 4 && request.status === 200) {
                        document.getElementById('js-installation-log').innerHTML = request.responseText;
                    }
                    readInstallationLog();
                }
            }
            window.addEventListener('installStarted', e => {
                @this.install();
                readInstallationLog();
            });
        </script>

    </div>
</div>
