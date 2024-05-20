<?php
namespace Microoculus\AllUploads\Services;
use Microoculus\AllUploads\Repositories\UploadsRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UploadsService {

    private UploadsRepository $uploadsRepository;
   

    public function __construct()
    {
        $this->uploadsRepository = new UploadsRepository();
       
    }

    public function getAll(){
        return $this->uploadsRepository->getAllUploads('desc');
    }

    public function getPaginate($limit = 16){
        return $this->uploadsRepository->paginate(["*"],$limit);
    }
    public function getcursorPaginate($sortOrder = "ASC", $where = [], $limit = 16){

        return $this->uploadsRepository->getFileMangerMedias(['admin_collection'], $where, $limit, $sortOrder);
    }

    public function getCursorPaginationSortByName($sortOrder = "ASC", $where = [], $limit = 16){
        return $this->uploadsRepository->sortFileMangerMediasByName(['admin_collection'], $where, $limit, $sortOrder);
    }
    public function store(Request $request, array $details = []){
       
        $upload =  $this->uploadsRepository->store($details);
        if ($request->hasFile('upload_media')) {
            $fileAdders = $upload->addMultipleMediaFromRequest(['upload_media'])
            ->each(function ($fileAdder) use($details) {
                $fileAdder->toMediaCollection($details['collection_name']);
            });
        }

        // if ($request->hasFile('upload_media')) {
        //     if(is_array($request->upload_media)){
        //         foreach($request->file('upload_media') as $media){
                   
        //             $upload ->addMedia($media)->toMediaCollection($details['collection_name']);
        //         }
        //     }else{
        //         $upload->addMediaFromRequest('upload_media')->toMediaCollection($details['collection_name']);
        //     }
        // }

        // TODO : Code flushup. Un-necessry code removing
        $upload->refresh();
        return $upload;

    }
   
}