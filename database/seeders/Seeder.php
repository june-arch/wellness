<?php

namespace Database\Seeders;

use App\Models\Users\Admin;
use Illuminate\Database\Seeder as DBSeeder;
use Illuminate\Support\Facades\Auth;

class Seeder extends DBSeeder
{
    protected function actingAs()
    {
        Auth::login(Admin::inRandomOrder()->first());
    }

    protected function addGalery($model)
    {
        for ($i = 0; $i < rand(2, 5); $i++) {
            $this->addFormStorage($model, 'galery');
        }
    }

    protected function addThumbnail($model)
    {
        $this->addFormStorage($model, 'thumbnail');
        // $model->addFile("https://source.unsplash.com/random/600x600")->toMediaCollection('thumbnail');
    }

    protected function addFiles($model)
    {
        for ($i = 0; $i < rand(1, 2); $i++) {
            // $model->addMediaFromUrl("https://source.unsplash.com/random/300x500")->toMediaCollection('files');
        }
    }

    protected function addFormStorage($model, $folder = 'thumbnail')
    {
        // $file = storage_path("app/public/media-uploads/" . rand(1, 9079) . ".jpeg");

        // if (file_exists($file)) {
        //     $model->addFile($file, $folder);
        // }
    }
}
