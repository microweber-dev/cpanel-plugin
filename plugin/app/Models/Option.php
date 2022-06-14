<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    /**
     * @param $key
     * @param $value
     * @param $group
     * @return bool
     */
    public static function updateOption($key, $value, $group) {

        $find = static::where('option_key', $key)->where('option_group', $group)->first();
        if ($find == null) {
            $find = new static();
            $find->option_key = $key;
            $find->option_group = $group;
        }

        $find->option_value = $value;

        return $find->save();
    }


    /**
     * @param $group
     * @return array
     */
    public static function getAll($group = 'default')
    {
        $options = [];
        foreach (static::where('option_group', $group)->get() as $option) {
            $options[$option->option_key] = $option->option_value;
        }
        return $options;
    }

    /**
     * @param $key
     * @param $group
     * @param $default
     * @return false|mixed
     */
    public static function getOption($key, $group = 'default', $default = false)
    {
        $find = static::where('option_key', $key)->where('option_group', $group)->first();

        if ($find !== null) {
          return $find->option_value;
        }

        return $default;
    }
}
