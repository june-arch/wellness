<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Collections\LogableWithThumbnailCollection;
use App\Http\Controllers\Controller;
use App\Models\Users\Log;

class LogController extends Controller
{
    protected $table = 'logs';

    public function index()
    {
        $this->query = Log::select('id', 'user_id', 'data', 'method', 'path', 'ip_address', 'logable_id', 'logable_type', 'created_at')
            ->with('admin:id,name', 'admin.thumbnail', 'logable');

        $this->filter(
            [
                'method',
                'path',
                'ip_address',
                'type',
                'created_at',
            ],
            [
                [
                    'name'   => 'user',
                    'table'  => 'users',
                    'fields' => ['name'],
                ],
            ]
        );

        return new LogableWithThumbnailCollection($this->query);
    }
}
