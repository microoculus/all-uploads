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
    'media_remot_all_uploads_url'=>  route("all-uploads.remote-list-uploads"))];
  

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
                'deleteUrl' => is_null( $this->deleteUrls) ? null :  $this->deleteUrls[$index]
            );
        } 
        return json_encode($preview, JSON_UNESCAPED_SLASHES);
    }

    public function DeleteUrls(array|String $urls){

        $this->deleteUrls = $urls;
       
        if(is_array($this->deleteUrls) && count($this->deleteUrls) != count($this->mediaIds)){
            throw new \Exception("Media and delete url count mismatch");
        }else if(is_string($this->deleteUrls) && isset($this->mediaIds) && is_array($this->mediaIds)){
                throw new \Exception("Media and delete url count mismatch");
        }else if(empty( $urls)){
            throw new \Exception("Parameter missing");
        }
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
   
}