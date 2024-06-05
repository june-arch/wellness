<?php

namespace App\Models\Media;

use App\Models\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{

    public $fillable = [
        'folder',
        'name',
        'path',
        'mime_type',
        'size',
        'disk',
        'order_column',
        'optimized',
    ];

    protected $casts = [
        'optimized' => 'boolean',
    ];

    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url("{$this->path}");
    }

    public function getAttributeWithUrlAttribute()
    {
        return collect($this)->only('name', 'mime_type', 'size')->put('url', $this->url);
    }

    public function unlink()
    {
        Storage::disk($this->disk)->delete($this->path);
        return $this->delete();
    }
}
