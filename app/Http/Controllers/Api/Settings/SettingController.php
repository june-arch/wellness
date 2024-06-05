<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;

class SettingController extends Controller
{
  public function index()
  {
    request()->user()->can('manage-setting');
  }
}
