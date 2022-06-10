<div>

    <form id="contactForm" data-sb-form-api-token="API_TOKEN">
        <div class="mb-3">
            <label class="form-label" for="defaultInstallationTemplate">Default Installation template</label>
            <select class="form-select" wire:model="state.installation_template" id="defaultInstallationTemplate" aria-label="Default Installation template">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label" for="defaultInstallationLanguage">Default Installation language</label>
            <select class="form-select" wire:model="state.installation_language" id="defaultInstallationLanguage" aria-label="Default Installation language">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label d-block">Default Installation type</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" id="default" type="radio" wire:model="state.installation_type" value="default" name="installation_type" data-sb-validations="required" />
                <label class="form-check-label" for="default">Default</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" id="symLinkedSavesABigAmountOfDiskSpace" type="radio" wire:model="state.installation_type" value="symlinked" name="installation_type" data-sb-validations="required" />
                <label class="form-check-label" for="symLinkedSavesABigAmountOfDiskSpace">Sym-Linked <small>(saves a big amount of disk space)</small></label>
            </div>
            <div class="invalid-feedback" data-sb-feedback="defaultInstallationType:required">One option is required.</div>
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" id="allowCustomersToChooseInstallationType" type="checkbox" wire:model="state.installation_type_allow_customers" value="1" name="installation_type_allow_customers" />
                <label class="form-check-label" for="allowCustomersToChooseInstallationType"> Allow customers to choose installation type </label>
            </div>
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" id="allowCustomersToChooseInstallationDatabaseDriver" type="checkbox" wire:model="state.installation_database_driver_allow_customers" value="1" name="installation_database_driver_allow_customers" />
                <label class="form-check-label" for="allowCustomersToChooseInstallationDatabaseDriver"> Allow customers to choose installation database driver </label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="databaseDriver">Database Driver</label>
            <select class="form-select" id="databaseDriver" name="installation_database_driver" wire:model="state.installation_database_driver" aria-label="Database Driver">
                <option value="sqlite">SQLite</option>
                <option value="mysql">MySql</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label" for="updateAppChannel">Update App Channel</label>
            <select class="form-select" id="updateAppChannel" name="update_app_channel" wire:model="state.update_app_channel" aria-label="Update App Channel">
                <option value="stable">Stable</option>
                <option value="developer">Developer</option>
            </select>
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" id="updateAppAutomatically" type="checkbox" wire:model="state.update_app_automatically" value="1" name="update_app_automatically" />
                <label class="form-check-label" for="updateAppAutomatically">Update App Automatically</label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="whmcsUrl">WHMCS Url</label>
            <input class="form-control" id="whmcsUrl" type="text" placeholder="WHMCS Url"  wire:model="state.whmcs_url" name="whmcs_url" data-sb-validations="" />
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" id="allowResellersToUseTheirOwnWhiteLabel" type="checkbox" wire:model="state.allow_reseller_whitelabel" value="1" name="allow_reseller_whitelabel" />
                <label class="form-check-label" for="allowResellersToUseTheirOwnWhiteLabel">Allow resellers to use their own White Label?</label>
            </div>
        </div>
    </form>

</div>
