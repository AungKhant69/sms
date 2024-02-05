<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ConfigController extends Controller
{
    public $pagination = 5;
    public $data = [];
    public function __construct(){

        $this->data = [
            'header_title' => 'Parents List',
            'getRecord' => [],
        ];
    }



}
