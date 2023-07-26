<?php
namespace Microoculus\AllUploads\Concerns;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Microoculus\AllUploads\Concerns\HasAllUploads;
use Carbon\Carbon;

trait HasUploads {

    use HasAllUploads;
    public function media(){
        return $this->belongsTo(Media::class, 'media_id');
    }
}