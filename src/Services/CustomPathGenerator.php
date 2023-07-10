<?php

namespace Microoculus\AllUploads\Services;

use Microoculus\AllUploads\Models\Uploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

/**
 * Class CustomPathGenerator
 * @package App\MediaLibrary
 */
class CustomPathGenerator implements PathGenerator
{
    /**
     * @param Media $media
     *
     * @return string
     */
    public function getPath(Media $media): string
    {
        switch ($media->model_type) {
            case Uploads::class:
                return Uploads::PATH.DIRECTORY_SEPARATOR.$media->id.DIRECTORY_SEPARATOR;
                break;
            default:
                return $media->id.DIRECTORY_SEPARATOR;
        }
    }

    /**
     * @param Media $media
     *
     * @return string
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media).'thumbnails/';
    }

    /**
     * @param Media $media
     *
     * @return string
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media).'rs-images/';
    }
}
