<?php

trait MicrowberLicenseDataTrait
{
    public function getLicenseData($whiteLabelKey = false)
    {

        if ($whiteLabelKey) {
            $whiteLabelKey = trim($whiteLabelKey);
            $relType = 'modules/white_label';
            $check_url = "https://update.microweberapi.com/?api_function=validate_licenses&local_key=$whiteLabelKey&rel_type=$relType";
            $data = file_get_contents($check_url);
            $data = @json_decode($data, true);
            if ($data and isset($data[$relType])) {
                $keyData = $data[$relType];
                return $keyData;
            }
        }

    }
}