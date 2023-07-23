<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrivacyPolicyController extends Controller
{
    public function index()
    {
        return view('admin.privacy_policy.privacy-policy');
    }
}
