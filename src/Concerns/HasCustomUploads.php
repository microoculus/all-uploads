<?php
namespace Microoculus\AllUploads\Concerns;
use Microoculus\AllUploads\Concerns\HasAllUploads;
use Carbon\Carbon;

trait HasCustomUploads {

    use HasAllUploads;
    // abstract method
    // public function media(){
    //     return $this->belongsTo(Media::class, 'media_id');
    // }

    abstract public function media();

   
}