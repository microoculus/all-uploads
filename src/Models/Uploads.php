<?php

namespace Microoculus\AllUploads\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Microoculus\AllUploads\Concerns\HasUuid;


class Uploads extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    use HasUuid;
    const PATH = 'uploads'.DIRECTORY_SEPARATOR.'all';

    protected $fillable = [
        'thread', 'collection_name', 'user_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $casts = [
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(368)
              ->height(232)
              ->sharpen(10)
            <?php

namespace Microoculus\AllUploads\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Microoculus\AllUploads\Concerns\HasUuid;


class Uploads extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    use HasUuid;
    const PATH = 'uploads'.DIRECTORY_SEPARATOR.'all';

    protected $fillable = [
        'thread', 'collection_name', 'user_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $casts = [
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(368)
              ->height(232)
              ->sharpen(10)
              ->nonQueued();
    }
}
;
    }
}
