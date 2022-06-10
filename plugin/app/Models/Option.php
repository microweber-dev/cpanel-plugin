<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    public static function updateOption($key, $value) {

        $find = static::where('option_key', $key)->first();
        if ($find == null) {
            $find = new static();
            $find->option_key = $key;
        }

        $find->option_value = $value;
        return $find->save();

    }


    public static function getAll()
    {
        $options = [];

        foreach (static::get() as $option) {
            $options[$option->option_key] = $option->option_value;
        }

        return $options;
    }

    public function getOption($key)
    {
        $find = static::where('option_key', $key)->first();
        if ($find != null) {
          return $find->option_value;
        }

        return false;
    }
}
