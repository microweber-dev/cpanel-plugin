<div>
    <h4>Choose installation options</h4>
    <p>
        Random values will be generated if fields are left blank.
    </p>

    <div id="install-form" class="mt-3">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="domain">Domain</label>
                    <select class="form-select" id="domain" wire:model.defer="installationDomain">
                        @foreach($domains as $domain)
                        <option value="1">{{$domain['domain']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="domain">Domain</label>
                    <select class="form-select" id="domain" wire:model.defer="installationDomain">
                        @foreach($domains as $domain)
                        <option value="1">{{$domain['domain']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="installationLanguage">Installation language</label>
            <select class="form-select" wire:model.defer="installationLanguage" id="installationLanguage">
                @foreach($supportedLanguages as $language=>$languageName)
                    <option value="{{$language}}">{{$languageName}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label" for="installationTemplate">Installation template</label>
            <select class="form-select" wire:model.defer="installationTemplate" id="installationTemplate">
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

        <div class="mb-3">
            <label class="form-label" for="adminEmail">Admin Email</label>
            <input class="form-control" id="adminEmail" type="email" wire:model.defer="installationAdminEmail" placeholder="Admin Email" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="adminUsername">Admin Username</label>
            <input class="form-control" id="adminUsername" type="text" wire:model.defer="installationAdminUsername" placeholder="Admin Username" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="adminPassword">Admin Password</label>
            <input class="form-control" id="adminPassword" type="password" wire:model.defer="installationAdminPassword" placeholder="Admin Password" />
        </div>

        <button class="btn btn-outline-success" wire:click="install()" type="button">Install</button>
    </div>

</div>
