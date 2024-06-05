<?php

namespace App\Models\Media;

use App\Jobs\OptimizeImage;
use App\Models\Media\File;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasFile
{
    protected static $DEFAULT_USER_THUMB_PATH  = 'assets/images/profile.jpg';
    protected static $DEFAULT_IMAGE_THUMB_PATH = 'assets/images/galery.jpg';

    public function files()
    {
        return $this->morphToMany(File::class, 'fileable');
    }

    public function removeFiles(array $ids = [])
    {
        $files = File::whereIn($ids)->get();

        foreach ($files as $file) {
            $file->unlink();
        }
    }

    public function galery()
    {
        return $this->morphToMany(File::class, 'fileable')
            ->where('folder', 'galery')
            ->orderBy('order_column', 'asc');
    }

    public function galeryUrl()
    {
        return $this->galery->map(function ($item) {
            return $item->getUrl;
        });
    }

    public function getFirstGaleryUrlAttribute()
    {
        if ($thumb = $this->galery->first()) {
            return $thumb->getUrl;
        }

        return null;
    }

    public function thumbnail()
    {
        return $this->morphToMany(File::class, 'fileable')
            ->where('folder', 'thumbnail')
            ->orderBy('order_column', 'asc');
    }

    public function media()
    {
        return $this->morphToMany(File::class, 'fileable')
            ->where('folder', 'media')
            ->orderBy('order_column', 'asc');
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::make(get:function () {
            if ($thumb = $this->thumbnail->first()) {
                return $thumb->url;
            }

            return asset(isset($this->role_id) ? self::$DEFAULT_USER_THUMB_PATH : self::$DEFAULT_IMAGE_THUMB_PATH);
        });
    }

    public function mediaUrl(): Attribute
    {
        return Attribute::make(get:function () {
            if ($thumb = $this->media->first()) {
                return $thumb->url;
            }
        });
    }

    public function getMedia(): Attribute
    {
        return Attribute::make(get:function () {
            if ($thumb = $this->media->first()) {
                return $thumb->attributeWithUrl;
            }
        });
    }

    public function addFile($file, $folder = null)
    {
        if (file_exists($file)) {
            $newPath = Storage::path($folder ? "{$folder}/" : '');

            if (!file_exists($newPath)) {
                mkdir($newPath, 0777, true);
            }

            $mime        = $file->getClientMimeType() ?? mime_content_type($file);
            $size        = filesize($file);
            $isImage     = Str::startsWith($mime, 'image');
            $name        = ($this->slug ?? $this->name ?? $this->id ?? Str::uuid()) . '-' . Str::random(rand(5, 8));
            $newFilePath = $newPath . Str::of($name)->slug()->trim('-')->replace('--', '-')->__toString() . $this->getExtension($mime);

            if (copy($file, $newFilePath)) {
                $file = $this->files()->create([
                    'folder'    => $folder,
                    'name'      => $name,
                    'path'      => Str::of($newFilePath)->replace(Storage::path(''), '')->__toString(),
                    'mime_type' => $mime,
                    'disk'      => config('filesystems.default'),
                    'size'      => $size,
                    'optimized' => !$isImage,
                ]);

                if ($isImage) {
                    OptimizeImage::dispatch($file);
                }
            }
        }
    }

    public function addFileFromRequest(UploadedFile $file, $folder)
    {
        if (file_exists($file)) {
            $name    = $this->slug ?? $this->name ?? $this->id;
            $mime    = $file->getClientMimeType();
            $isImage = Str::startsWith($mime, 'image');
            $size    = $file->getMaxFilesize();
            $path    = $file->storeAs(
                $folder,
                trim(Str::of($name)->slug()->explode('-')->take(4)->join('-') . '-' . Str::uuid(), '-') . $this->getExtension($mime)
            );

            $file = $this->files()->create([
                'folder'    => $folder,
                'name'      => $name,
                'path'      => $path,
                'mime_type' => $mime,
                'size'      => $size,
                'optimized' => !$isImage,
            ]);

            if ($isImage) {
                OptimizeImage::dispatch($file);
            }

            return $file;
        }
    }

    public function saveAvatar(Request $request, $key = 'avatar'): void
    {
        if ($request->has($key)) {
            $file = $request->file($key);
            $this->addFileFromRequest($file, 'avatar');
        }
    }

    public function saveThumbnail(Request $request, $key = 'thumbnail'): void
    {
        if ($request->has($key)) {
            $oldFile = $this->thumbnail;
            $file    = $request->file($key);
            $file    = $this->addFileFromRequest($file, 'thumbnail');

            $this->thumbnail()->detach($oldFile->pluck('id'));

            foreach ($oldFile as $thumb) {
                $thumb->unlink();
            }
        }
    }

    public function saveMedia(Request $request, $key = 'media'): void
    {
        if ($request->has($key)) {
            $oldFile = $this->media;
            $file    = $request->file($key);
            $file    = $this->addFileFromRequest($file, 'media');
            $this->media()->detach($oldFile->pluck('id'));

            foreach ($oldFile as $thumb) {
                $thumb->unlink();
            }
        }
    }

    public function saveFiles(Request $request, $key = 'files', $folder = 'galery'): void
    {
        if ($request->has($key)) {
            foreach ($request->file($key) as $file) {
                $this->addFileFromRequest($file, $folder);
            }
        }
    }

    public function savegalery(Request $request): void
    {
        if ($request->has('galery')) {
            foreach ($request->file('galery') as $file) {
                $this->addFileFromRequest($file, 'galery');
            }
        }
    }

    protected function getExtension(string $mime)
    {
        return "." . explode('/', $mime)[1];
    }
}
