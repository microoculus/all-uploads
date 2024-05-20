<?php

namespace Microoculus\AllUploads\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Microoculus\AllUploads\Services\UploadsService;
use Carbon\Carbon;


class UploadController extends Controller
{
    private UploadsService $uploadsService;
    private $user;
    public function __construct()
    {
        $this->uploadsService = new UploadsService();
       
    }
    
    public function all(Request $request){
        if($request->ajax()){

            if($request->has('filter')){

                if($request->has('date_range')){

                    $startDate = Carbon::createFromFormat('d/m/Y', '13/04/2023');
                    $endDate = Carbon::createFromFormat('d/m/Y', '15/04/2023');
                    $where = [ ['created_at', '>=', $startDate->format('Y-m-d') ], ['created_at', '<=', $endDate->format('Y-m-d')]];
                    $uploadedMedias =  $this->uploadsService->getcursorPaginate("DESC", $where);
                }
            }else if($request->has('sorting')){
                $uploadedMedias =  $this->uploadsService->getcursorPaginate($request->input('sorting'));
            }else{

                $uploadedMedias =  $this->uploadsService->getcursorPaginate("DESC");
            }
            return  view('admin.media-upload.all-uploads', compact('uploadedMedias'))->render();
            
        }else{
            $uploadedMedias =  $this->uploadsService->getcursorPaginate("DESC");
        
            return  view('admin.media-upload.all-uploads', compact('uploadedMedias'))->render();
        }
    }

  
    public function store(Request $request)
    {

        ini_set('max_execution_time', 0);
        if($request->ajax()){

            try { 
                DB::beginTransaction();
                $details = array_merge(
                    ['collection_name' => 'admin_collection'],
                    ($request->has('user_id') ? ['user_id'=>$request->user_id] : [])
                );
                $uploads  = $this->uploadsService->store($request, $details);
                DB::commit();
                return response()->json([
                    'sucess' => true,
                    'threadId'=>$uploads->id
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                $exceptionMessage = $e->getMessage();
                return response()->json([
                    "message" =>  $exceptionMessage,
                    'sucess' => false,
                     ]);
            }
        }else{

        }
    }


    public function deleteMediaById(Request $request){
       return \AllUploads::deleteMediaById($request->mediaId);
    }
}
