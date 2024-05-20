<?php

namespace Microoculus\AllUploads\Repositories;
use Illuminate\Database\Eloquent\Model;  
use Microoculus\AllUploads\Models\Uploads;
use Microoculus\AllUploads\Interfaces\UploadsRepositoryInterface;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UploadsRepository extends BaseRepository implements UploadsRepositoryInterface
{     
    public function __construct(Uploads $model = new Uploads())
    {
        parent::__construct($model);
    }

    public function getAllUploads(){
        return $this->getAll();
    }

    public function getFileMangerMedias($mediCollectionName = ['admin_collection'], $where = [], $limit = 16, $sortOrder="ASC" ){
        return $this->model
        ->whereHas('media', function($q) use ($mediCollectionName){
            $q->whereIn('collection_name', $mediCollectionName)
            ->whereNotIn('collection_name', ['deleted']);
        })
        ->when($where, function ($q) use ($where) {
            foreach ($where as $field => $value) {
                if (is_array($value)) {
                    list($field, $condition, $val) = $value;
                   $q->where($field, $condition, $val);
                } else {
                    $q->where($field, '=', $value);
                }
            }
        })
        ->orderBy('id', $sortOrder)->cursorPaginate( $limit);
    }

    public function sortFileMangerMedias($mediCollectionName = ['admin_collection'], $where = [], $limit = 16, $sortOrder="ASC" ){

           
      return $this->model
        ->whereHas('media', function($q) use ($mediCollectionName){
            $q->whereIn('collection_name', $mediCollectionName)
            ->whereNotIn('collection_name', ['deleted']);
        })
        ->when($where, function ($q) use ($where) {
            foreach ($where as $field => $value) {
                if (is_array($value)) {
                    list($field, $condition, $val) = $value;
                   $q->where($field, $condition, $val);
                } else {
                    $q->where($field, '=', $value);
                }
            }
        })
        ->orderBy('id', $sortOrder)->cursorPaginate( $limit);
       
    }
    public function sortFileMangerMediasByName($mediCollectionName = ['admin_collection'], $where = [], $limit = 16, $sortOrder="ASC" ){
       
        $mediasIds  = Media::select(["model_id", "name"])->where("model_type", "Microoculus\AllUploads\Models\Uploads")
        ->whereIn('collection_name', $mediCollectionName)
        ->whereNotIn('collection_name', ['deleted'])
        ->orderBy('name', $sortOrder)
        ->get()
        ->pluck('model_id')
        ->toArray();

     return $this->model
        ->when($where, function ($q) use ($where) {
            foreach ($where as $field => $value) {
                if (is_array($value)) {
                    list($field, $condition, $val) = $value;
                   $q->where($field, $condition, $val);
                } else {
                    $q->where($field, '=', $value);
                }
            }
        })
        ->whereIn('id',  $mediasIds)
        ->orderByRaw('FIELD(id, '.implode(", " ,  $mediasIds).')')
        ->cursorPaginate( $limit);
    }
 
}