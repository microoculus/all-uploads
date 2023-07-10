$(function() {
    'use strict';
   var csrf_token =  document.querySelector('meta[name="csrf-token"]').getAttribute('content');
   if(typeof window.all_uploads.allUploadsConf == undefined){
    throw new Error("all up loads script error! Global function not defined");
   }
 

});
    function uploadZoneInit(){
            const element = document.querySelector("#upload_media");
            if(element){
                $(element).fileinput({
                    browseOnZoneClick: true,
                    uploadUrl: window.all_uploads.allUploadsConf('media_upload_ulr'),
                    uploadAsync:false,
                    showClose :false,
                    uploadExtraData:{'_token':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                });

                /**
                 * Multiple request
                 */

                // $(element).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
                //     console.log('File batch upload complete', preview, config, tags, extraData);
                   
                //     if(document.querySelector("#media-upload-ful-screen-modal")) {
                //         allUploadedMediaLoadInit();
                //         $("#media-upload-ful-screen-modal").modal("hide");
                //         $(document.body).removeClass('modal-open');
                //         $('.modal-backdrop').remove();
                //     }

                // });

                /**
                 * Single request
                 */

                $(element).on('filebatchuploadsuccess', function(event, data) {
                    if(document.querySelector("#media-upload-ful-screen-modal")) {
                        allUploadedMediaLoadInit();
                        $("#media-upload-ful-screen-modal").modal("hide");
                        $(document.body).removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    }
                });
            }
          
        
    }

    function allUploadedMediaLoadInit(){
       
        const element = document.querySelector("#all-uploaded-media");
        if(element){
            $.ajax({
                url:  window.all_uploads.allUploadsConf('media_all_uploads_url'),
                type: 'GET',
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                beforeSend: function() {
                    // setting a timeout
                    $(element).html('loading...');
                },
                success: function(response) {
                    $(element).empty().html(response);
                   if(document.querySelector(".remote-uploader-body")) {
                    allUploadedMediaPaginateLoadInit();
                    allUploadedMediaSortingLoadInit();
                   
                   }
                    
                }
            });
        }
    }
    function allUploadedMediaPaginateLoadInit(){
        if(document.querySelector(".custom-pagination-nav")){
            $('.custom-pagination-nav').on("click", function(event){
                event.preventDefault();
                // window.media_all_uploads_url = $(this).attr("href");
                window.all_uploads.setAllUploadsConf("media_all_uploads_url", $(this).attr("href")) 
                allUploadedMediaLoadInit();
            })
        }else  if(document.querySelector(".pagination .page-item a.page-link")){
            $('.pagination .page-item a.page-link').on("click", function(event){
                event.preventDefault();
                // window.media_all_uploads_url = $(this).attr("href");
                window.all_uploads.setAllUploadsConf("media_all_uploads_url", $(this).attr("href"))
                allUploadedMediaLoadInit();
            })
        }else{
            console.log("here")
        }

    }

    function allUploadedMediaSortingLoadInit(){
        if(document.querySelector(".media-sort")){
            $(".media-sort").find("[data-sortingOption]").each(function(){
                $(this).off("click").on("click",function(){
                   let mediaAllUploadsUrl = new URL( window.all_uploads.allUploadsConf("media_all_uploads_url"));
                   mediaAllUploadsUrl.searchParams.set("sorting", $(this).attr("data-sortingOption")); 
                   mediaAllUploadsUrl = mediaAllUploadsUrl.href;
                   window.all_uploads.setAllUploadsConf("media_all_uploads_url", mediaAllUploadsUrl)
                    allUploadedMediaLoadInit();
                });
            });
        }
    }

  
