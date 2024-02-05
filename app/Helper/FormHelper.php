<?php

namespace App\Helper;

use App\Models\ConfigModel;

class FormHelper
{
    public static function getProfile($url)
    {
        if (!empty($url) && file_exists('storage/uploads/' . $url)) {
            return '<img src="' .  asset('storage/uploads/' . $url)  . '" style="height: 50px; width: 50px; border-radius: 50px">';
        } else {
            return "";
        }
    }

    public static function getConfig()
    {
        return ConfigModel::latest()->first()->toArray();
    }
}
