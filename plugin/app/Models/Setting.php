<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public static function updateSetting($key, $value) {

        $find = static::where('setting_key', $key)->first();
        if ($find == null) {
            $find = new static();
            $find->setting_key = $key;
        }

        $find->setting_value = $value;
        return $find->save();

    }


    public static function getAll()
    {
        $settings = [];

        foreach (static::get() as $setting) {
            $settings[$setting->setting_key] = $setting->setting_value;
        }

        return $settings;
    }

    public function getSetting($key)
    {
        $find = static::where('setting_key', $key)->first();
        if ($find != null) {
          return $find->setting_value;
        }

        return false;
    }
}
