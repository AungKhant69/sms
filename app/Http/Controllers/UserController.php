<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\BusinessEmailModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $data = [];
    public function __construct()
    {
        $this->data = [
            'header_title' => 'Business Email',
            'getRecord' => [],
        ];
    }
}
