<?php

namespace App\Jobs;

use App\Models\Media\File;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class OptimizeImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function handle()
    {
        try {
            if ($this->file->optimized === false) {
                ImageOptimizer::optimize(Storage::disk($this->file->disk)->path($this->file->path));
                $this->file->optimized = true;
                $this->file->save();
            }
        } catch (Exception $err) {
            throw $err;
        }
    }
}
