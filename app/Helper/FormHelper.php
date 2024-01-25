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

    // public static function getDocumentLink($documentFile, $download = false)
    // {
    //     $documentPath = public_path('homework/' . $documentFile);

    //     if (!empty($documentFile) && file_exists($documentPath) && pathinfo($documentFile, PATHINFO_EXTENSION) == 'pdf') {
    //         $linkText = $download ? 'Download PDF' : 'View PDF';

    //         // Return the link with target attribute for view or download attribute for download
    //         return '<a href="' . asset('homework/' . $documentFile) . '" ' . ($download ? 'download="' . $documentFile . '"' : 'target="_blank"') . '>' . $linkText . '</a>';
    //     }

    //     return null;
    // }

    // public static function getDocument($documentFile)
    // {
    //     if (!empty($documentFile) && file_exists('uploads/homework/' . $documentFile)) {
    //         return url('uploads/homework/' . $documentFile);
    //     } else {
    //         return null;
    //     }
    // }

    public static function getConfig()
    {
        return ConfigModel::latest()->first()->toArray();
    }
}
