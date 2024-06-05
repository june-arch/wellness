<?php

namespace App\Http\Controllers\Api\Users\Admins;

use App\Http\Collections\LogableCollection;
use App\Http\Controllers\Controller;
use App\Repositories\AdminLogRepository;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminLogRepository::index($request);

        return $this->collection(new LogableCollection($query));
    }
}
