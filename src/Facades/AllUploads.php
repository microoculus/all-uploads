<?php
namespace Microoculus\AllUploads\Facades;
use Illuminate\Support\Facades\Facade;

class AllUploads extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'alluploads';
    }
}