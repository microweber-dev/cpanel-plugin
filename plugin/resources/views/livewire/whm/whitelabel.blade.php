<div>

    @if(!$activeWhitelabel)
        <div>
            <h4>Use White Label license to grow your business!</h4>
            <p>The <b>Whitelabel</b> gives you the right to rebrand <b>Microweber CMS</b> with your own logo.</p>
            <p>You can sell it to your clients under your own brand. There is no limitation of number of installations.</p>

            <p><a href="" class="btn btn-outline-primary" target="_blank">Get your Whitelabel license here</a></p>

            <div class="card">
                <div class="card-body">
                    <div id="whitelabel-form" style="width: 550px;">

                        @if($validationMessageWhitelabelKey)
                            <div class="alert alert-danger">{{$validationMessageWhitelabelKey}}</div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label" for="whiteLabelKey">White Label Key </label>
                            <input class="form-control" id="whiteLabelKey" type="text" wire:model.defer="whitelabelLicenseKey" placeholder="Place your microweber White Label key..." />
                        </div>
                        <div>
                            <button class="btn btn-outline-primary" wire:click="validateLicense()" type="submit">Validate License</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endif

    @if($activeWhitelabel)

    <div class="alert alert-primary">
        <p>
            Registered Name: <b>{{$licenseKeyDetails['register_name']}}</b> <br>
            Company Name: <b>{{$licenseKeyDetails['company_name']}}</b> <br>
            Email: <b>{{$licenseKeyDetails['email']}}</b> <br>
            Billing Cycle: <b> {{$licenseKeyDetails['billing_cycle']}}</b><br>
            Due on: <b>{{$licenseKeyDetails['next_due_date']}}</b><br>
            Registraion on: <b>{{$licenseKeyDetails['register_date']}}</b><br>
        </p>

        @if($confirmRemoveLicense)
            <button wire:click="removeLicense()" class="btn btn-outline-dark btn-sm">
                Are you sure want to delete whitelabel license key?
            </button>
        @else
            <button wire:click="confirmRemoveLicense()" class="btn btn-outline-danger btn-sm">
                Remove Whitelabel Key
            </button>
        @endif

    </div>

    <div id="whitelabel-form">

        <div class="mb-3">
            <label class="form-label" for="brandName">Brand Name</label>
            <input class="form-control" id="brandName" wire:model="state.brand_name" type="text" placeholder="Brand Name" data-sb-validations="" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="brandFavicon">Brand Favicon</label>
            <input class="form-control" id="brandFavicon" wire:model="state.brand_favicon" type="text" placeholder="Brand Favicon" data-sb-validations="" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="adminLoginWhiteLabelUrl">Admin login - White Label URL?</label>
            <input class="form-control" id="adminLoginWhiteLabelUrl" wire:model="state.admin_logo_login_link" type="text" placeholder="Admin login - White Label URL?" data-sb-validations="" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="enableSupportLinks">Custom support links</label>
            <input class="form-control" id="enableSupportLinks" wire:model="state.custom_support_url" type="text" placeholder="Enable support links?" data-sb-validations="" />
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" id="enableSupportLinks" wire:model="state.enable_service_links" type="checkbox" name="enableSupportLinks" />
                <label class="form-check-label" for="enableSupportLinks">Enable support links</label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="enterPoweredByText">Enter &quot;Powered by&quot; text </label>
            <textarea class="form-control" id="enterPoweredByText" wire:model="state.powered_by_link" type="text" placeholder='Enter "Powered by" text ' style="height: 10rem;" data-sb-validations=""></textarea>
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" id="hidePoweredByLink" wire:model="state.disable_powered_by_link" type="checkbox" name="hidePoweredByLink" />
                <label class="form-check-label" for="hidePoweredByLink">Hide &quot;Powered by&quot; link </label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="logoForAdminPanelSize180X35Px">Logo for Admin panel (size: 180x35px) </label>
            <input class="form-control" id="logoForAdminPanelSize180X35Px" wire:model="state.logo_admin" type="text" placeholder="Logo for Admin panel (size: 180x35px) " data-sb-validations="" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="logoForLiveEditToolbarSize50X50Px">Logo for Live-Edit toolbar (size: 50x50px) </label>
            <input class="form-control" id="logoForLiveEditToolbarSize50X50Px" wire:model="state.logo_live_edit" type="text" placeholder="Logo for Live-Edit toolbar (size: 50x50px) " data-sb-validations="" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="logoForLoginScreenMaxWidth290Px">Logo for Login screen (max width: 290px) </label>
            <input class="form-control" id="logoForLoginScreenMaxWidth290Px" wire:model="state.logo_login" type="text" placeholder="Logo for Login screen (max width: 290px) " data-sb-validations="" />
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" id="disableMicroweberMarketplace" wire:model="state.disable_marketplace" type="checkbox" name="disableMicroweberMarketplace" />
                <label class="form-check-label" for="disableMicroweberMarketplace"> Disable Microweber Marketplace </label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="externalLoginServerButtonText">External Login Server Button Text </label>
            <input class="form-control" id="externalLoginServerButtonText" wire:model="state.external_login_server_button_text" type="text" placeholder="External Login Server Button Text " data-sb-validations="" />
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" id="enableExternalLoginServer" wire:model="state.external_login_server_enable" type="checkbox" name="enableExternalLoginServer" />
                <label class="form-check-label" for="enableExternalLoginServer">Enable External Login Server </label>
            </div>
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" id="enableMicroweberServiceLinks" wire:model="state.enable_service_links" type="checkbox" name="enableMicroweberServiceLinks" />
                <label class="form-check-label" for="enableMicroweberServiceLinks">Enable Microweber Service Links</label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="cpanelLogoForSidebar">Cpanel Logo for sidebar</label>
            <input class="form-control" id="cpanelLogoForSidebar" wire:model="state.cpanel_logo_invert" type="text" placeholder="Cpanel Logo for sidebar" data-sb-validations="" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="cpanelLogoApp">Cpanel Logo App</label>
            <input class="form-control" id="cpanelLogoApp" wire:model="state.cpanel_logo_app" type="text" placeholder="Cpanel Logo App" data-sb-validations="" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="enterAdminColorsSass">Enter &quot;Admin colors&quot; sass</label>
            <textarea class="form-control" id="enterAdminColorsSass" wire:model="state.admin_colors_sass" type="text" placeholder='Enter "Admin colors" sass' style="height: 10rem;" data-sb-validations=""></textarea>
        </div>
    @endif
</div>
