<form class="form-horizontal1" method="post">
    <input type="hidden" name="_action" value="_save_whmcs_connector_settings"/>

    <div class="row">


        <div class="col-md-8">

            <div class="form-group">
                <label for="whmcs_url" class="control-label">WHMCS Integration</label>
                <input type="text" name="whmcs_url" class="form-control" id="whmcs_url"
                       placeholder="Enter the URL of your WHMCS for example: https://members.example.com/"
                       value="<?= isset($whmcs_connector_settings['whmcs_url']) ? $whmcs_connector_settings['whmcs_url'] : ''; ?>">
                <br>
                <small>You must install the Microweber WHMCS addon from <a
                            href="https://microweber.org/go/whmcs" target="_blank">this link</a>
                    and then enter the url of your WHMCS installation for example: https://members.example.com/
                </small>
            </div>

            <div class="">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</form>


