<?php
namespace Microoculus\AllUploads;
use Microoculus\AllUploads\Concerns\HasAllUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Collection;


class AllUploads{

    use HasAllUploads;
    protected int $mediaId;
    protected Array $mediaIds;
    protected Mixed  $deleteUrls = null;
    protected Media $media;
    protected Collection $medias;
    protected Array $config = [] ;
    private $getjsHelperName = 'allUploadsConf';
    private $setjsHelperName = 'setAllUploadsConf';
    private $jsNamespace = 'all_uploads';

    

   public function  Media(int $id){
        $this->mediaId = $id;
        $this->media = Media::find($this->mediaId);
        return  $this;   
   }

   public function  Medias(Array $ids){
        $this->mediaIds = $ids;
        $this->medias = Media::whereIn('id', $this->mediaIds)->get();
        return  $this;   
   }

   public function getConfig(){

    return [... $this->config,...array('media_upload_url'=>  route("all-uploads.store-upload"),
    'media_all_uploads_url'=>  route("all-uploads.cursor-paginate-uploads"),
    'media_remot_all_uploads_url'=>  route("all-uploads.remote-list-uploads"),
    'media_delete_url'=>  route('all-uploads.media-delete'),
    )];
  

   }
   
   public function Config($config = []){
        foreach($config as $key => $value){
            $this->config[$key]= $value;
        }
        return  $this;  
   }
   public function Script($args = []){
    echo "<script src=".URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/js/plugins/buffer.min.js'). " type='text/javascript'></script>".PHP_EOL;
    echo "<script src=".URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/js/plugins/filetype.min.js'). " type='text/javascript'></script>".PHP_EOL;
    echo "<script src=".URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/js/plugins/piexif.min.js'). " type='text/javascript'></script>".PHP_EOL;
    echo "<script src=".URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/js/plugins/sortable.min.js'). " type='text/javascript'></script>".PHP_EOL;
    echo "<script src=".URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/js/fileinput.min.js'). " type='text/javascript'></script>".PHP_EOL;
    echo "<script src=".URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/js/locales/LANG.js'). " type='text/javascript'></script>".PHP_EOL;
    echo "<script src=".URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/themes/bs5/theme.js'). " type='text/javascript'></script>".PHP_EOL;

    echo $this->render();

    if (!empty($args) && array_key_exists("input",$args) && $args['input']){
        echo "<script src=".URL::asset('vendor/all-uploads/js/remote-uploader.js'). " type='text/javascript'></script>".PHP_EOL;
    }else if  (!empty($args) && array_key_exists("media",$args) && $args['media']){
        echo "<script src=".URL::asset('vendor/all-uploads/js/all-uploads.js'). " type='text/javascript'></script>".PHP_EOL;
    }

    
   }

   public function Style($args = []){
    echo "<link href=".URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/css/fileinput.min.css'). " rel='stylesheet'  type='text/css' />".PHP_EOL;
    echo "<link href=".URL::asset('vendor/all-uploads/bootstrap-icons/font/bootstrap-icons.css'). " rel='stylesheet'  type='text/css' />".PHP_EOL;

    if (!empty($args) && array_key_exists("fs",$args) && $args['fs']){
        echo "<link href=". URL::asset('vendor/all-uploads/fontawesome/css/all.css'). " rel='stylesheet'  type='text/css' />".PHP_EOL;
    }
    if (!empty($args) && array_key_exists("input",$args) && $args['input']){
        echo "<link href=". URL::asset('vendor/all-uploads/css/remote-uploader.css'). " rel='stylesheet'  type='text/css' />".PHP_EOL;
    }else if  (!empty($args) && array_key_exists("media",$args) && $args['media']){
        echo "<link href=".URL::asset('vendor/all-uploads/css/all-uploads.css'). " rel='stylesheet'  type='text/css' />".PHP_EOL;
    }
    
  

   }

   public function Input(Array $args = []){
    $default_args = ['class'=>"remote-uploader", 'name'=>"media"];
    $args = array_merge($default_args,$args);
    $data = str_replace("=", '="', http_build_query($args, null, '" ', PHP_QUERY_RFC3986)).'"';
    echo "<input  type='hidden'". rawurldecode($data) ."/>";
   }


   public function getJsHelpers(): string
    {
        $helpers = "";
        // $helpers .= 'window["'.$this->jsNamespace.'"].prototype.'.$this->getjsHelperName.'=function(prop){ if(this.hasOwnProperty(prop)){ return this[prop];}else{return null;}}';
        // $helpers .= 'window["'.$this->jsNamespace.'"].prototype.'.$this->setjsHelperName.'=function(prop, value){ if(this.hasOwnProperty(prop)){ this[prop] = value; return true;} else { return null; }}';
        return  $helpers;
    }

    public function render(array $options = []): string
    {
        $config = json_encode($this->getConfig(),JSON_UNESCAPED_SLASHES|JSON_FORCE_OBJECT );
        return <<<HTML
        <script type="text/javascript"> window["{$this->jsNamespace}"] = {$config};window["{$this->jsNamespace}"].{$this->getjsHelperName}=function(prop){ if(this.hasOwnProperty(prop)){ return this[prop];}else{return null;}}; window["{$this->jsNamespace}"].{$this->setjsHelperName}=function(prop, value){if(this.hasOwnProperty(prop)){this[prop] = value;return true;}else{return null;}}
        </script>
        HTML;
    }

    public function Manage(array $options = []){
        // $view = $this->getIncludeContents(__DIR__.DIRECTORY_SEPARATOR."resources".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."all-uploads-list.blade.php");
        $view =  view('AllUploads::all-uploads-list')->render();
        echo <<<HTML
        $view
        HTML;
    }

    public function Preview(array $options = []){
        $preview = [];
        $previvewCollection = empty( $this->mediaIds ) ? collect([$this->media] ) :  $this->medias;
        foreach($previvewCollection as $index => $media){
            $preview[] =  array(
                'url'=>$media->getFullUrl(), 
                'key' =>$media->id,
                'deleteUrl' => is_null( $this->deleteUrls) ? route('all-uploads.non-url-delete') :  (is_array($this->deleteUrls) ? $this->deleteUrls[$index] : $this->deleteUrls)
            );
        } 
        return json_encode($preview, JSON_UNESCAPED_SLASHES);
    }

    public function DeleteUrls(null|String|array $urls = null){
        // $this->deleteUrls = is_null($urls) ? route('all-uploads.non-url-delete') : $urls;
        $this->deleteUrls = $urls;
        if(is_array($this->deleteUrls) && count($this->deleteUrls) != count($this->mediaIds)){
            throw new \Exception("Media and delete url count mismatch");
        }else if(is_string($this->deleteUrls) && isset($this->mediaIds) && is_array($this->mediaIds)){
                throw new \Exception("Media and delete url count mismatch");
        }
        // else if(empty( $urls)){
        //     throw new \Exception("Parameter missing");
        // }
        return  $this;  

        //
    }


   private function getIncludeContents($included_file_path){
        ob_start();		
        include($included_file_path);
        $contents = ob_get_contents();
        ob_end_clean();
        echo $contents;
    }
   
    public function deleteMediaById(int $id){
        try{
            $this->media = Media::find($id);
            $this->media->update(['collection_name' => 'deleted']);
            return response()->json([
                'success' => true,
                "message" => "Successfully deleted",
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                "message" => $e->getMessage()
            ]);
        }
       
    }

    private function getDeletedImageBase64(){
       return  $notFoudImage = "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";
    }
}