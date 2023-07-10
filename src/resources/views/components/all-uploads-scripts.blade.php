@props([
    'user_id'=>null,
    'user_status'=>false,
   
])

<script src="{{ URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/js/plugins/buffer.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/js/plugins/filetype.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/js/plugins/piexif.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('vendor/all-uploads/kartik-v-bootstrap-fileinput/js/locales/LANG.js') }}" type="text/javascript"></script>


<script>

        window['all_uploads'] = {
            'user_id': @json($user_id), 
            'user_id_status':@json($user_status) ,
            'media_upload_ulr' : @json( route("all-uploads.store-upload") ),
            'media_all_uploads_url' : @json( route("all-uploads.cursor-paginate-uploads") ),
            'media_remot_all_uploads_url' : @json( route("all-uploads.remote-list-uploads") ),
            "allUploadsConf" : function(prop){
                if(this.hasOwnProperty(prop)){
                   return this[prop];
                }else{
                    return null;
                }
            },
            "setAllUploadsConf" : function(prop, value){
                if(this.hasOwnProperty(prop)){
                    this[prop] = value;
                    return true;
                }else{
                    return null;
                }
            },
        };

window.onload = function() {
    if (window.jQuery) {  
        if (!$.fn.modal) {
                throw new Error('Bootstrap is not loaded!');
            } else {

                    $('#remote-uploader-modal').on('shown.bs.modal', function (e) {
                    let modalDom = e.target;
                    let deatilsDom = e.relatedTarget;
                    $(modalDom).find(".uploaded-at").html($(deatilsDom).attr('data-details-uploaded-at'))
                    .end().find(".file-name").html($(deatilsDom).attr('data-details-file-name'))
                    .end().find(".file-type").html($(deatilsDom).attr('data-details-file-type'))
                    .end().find(".file-size").html($(deatilsDom).attr('data-details-file-size'))
                    .end().find(".file-url").val($(deatilsDom).attr('data-details-file-url'))
                    .end().find(".feature-image").attr("src", $(deatilsDom).attr('href'))

                    $(document).on('click', ".url-copy" , function() {
                        navigator.clipboard.writeText($('.file-url').val());
                    });
                    
                    }).on('hide.bs.modal', function (e) {
                        let modalDom = e.target;
                        $(modalDom).find(".uploaded-at").html("")
                        .end().find(".file-name").html("")
                        .end().find(".file-type").html("")
                        .end().find(".file-size").html("")
                        .end().find(".file-url").val("")
                        .end().find(".feature-image").attr("src", "")
                    });
            }

            $(function() {
                uploadZoneInit();
                allUploadedMediaLoadInit();
                $("input.remote-uploader").remoteUploader();

            });
           
    } else {
        throw new Error('Jquery is not loaded!');
    }

   
}
</script>
<script src="{{ URL::asset('vendor/all-uploads/js/all-uploads.js') }}"></script>
<script src="{{ URL::asset('vendor/all-uploads/js/remote-uploader.js') }}"></script>