<?php
namespace Microoculus\AllUploads\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuid
{
    public static function bootHasUuid()
    {
        static::creating(function (Model $model) {
            if (empty($model->thread)) {
                $model->thread = (string) Str::uuid();
            }
        });
    }

    public static function findByUuid(string $uuid): ?Model
    {
        return static::where('thread', $uuid)->first();
    }
}
