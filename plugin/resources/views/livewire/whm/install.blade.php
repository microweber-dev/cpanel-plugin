<div>
    <h3>Choose installation options</h3>
    <p>
        Random values will be generated if fields are left blank.
    </p>

    <div id="install-form">
        <div class="mb-3">
            <label class="form-label" for="domain">Domain</label>
            <select class="form-select" id="domain" aria-label="Domain">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label" for="installationLanguage">Installation language</label>
            <select class="form-select" wire:model="installation_language" id="installationLanguage" aria-label="Installation language">
                @foreach($supportedLanguages as $language=>$languageName)
                    <option value="{{$language}}">{{$languageName}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label" for="installationTemplate">Installation template</label>
            <select class="form-select" wire:model="installation_template" id="installationTemplate" aria-label="Installation template">
                @foreach($supportedTemplates as $template)
                    <option value="{{$template['targetDir']}}">{{$template['name']}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label d-block">Installation type</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" id="default" type="radio" wire:model="installation_type" value="default" name="installation_type"  />
                <label class="form-check-label" for="default">Default</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" id="symLinkedSavesABigAmountOfDiskSpace" type="radio" wire:model="installation_type" value="symlinked" name="installation_type"  />
                <label class="form-check-label" for="symLinkedSavesABigAmountOfDiskSpace">Sym-Linked <small>(saves a big amount of disk space)</small></label>
            </div>
            <div class="invalid-feedback" data-sb-feedback="defaultInstallationType:required">One option is required.</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="databaseDriver">Database Driver</label>
            <select class="form-select" id="databaseDriver" name="installation_database_driver" wire:model="installation_database_driver" aria-label="Database Driver">
                <option value="sqlite">SQLite</option>
                <option value="mysql">MySql</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label class="form-label" for="adminEmail">Admin Email</label>
            <input class="form-control" id="adminEmail" type="email" placeholder="Admin Email" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="adminUsername">Admin Username</label>
            <input class="form-control" id="adminUsername" type="text" placeholder="Admin Username" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="adminPassword">Admin Password</label>
            <input class="form-control" id="adminPassword" type="password" placeholder="Admin Password" />
        </div>

        <button class="btn btn-outline-success" id="submitButton" type="button">Install</button>
    </div>

</div>
