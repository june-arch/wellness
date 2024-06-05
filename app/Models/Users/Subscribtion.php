<?php

namespace App\Models\Users;

use App\Models\Model;

class Subscribtion extends Model
{
    public $fillable = ['user_id', 'name', 'follow'];

    public function admins()
    {
        return $this->morphedByMany(Admin::class, 'subscribtionable');
    }

    public static function notify(string $key, $notification): void
    {
        if ($key) {
            if ($subscribtion = self::firstWhere('key', $key)) {
                foreach ($subscribtion->families as $family) {
                    $family->notify($notification);
                }
            }
        }
    }
}
