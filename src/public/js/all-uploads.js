'use strict';
if (!window.jQuery) {
    throw new Error("Jquery not loaded");
}



$(function() {
    
   var csrf_token =  document.querySelector('meta[name="csrf-token"]').getAttribute('content');
   if(typeof window.all_uploads.allUploadsConf == undefined){
    throw new Error("Core script downloading error. script error! Global function not defined");
   }

   if (!$.fn.modal) {
    throw new Error('Bootstrap is not loaded!');
    }
   uploadZoneInit();
   allUploadedMediaLoadInit();
   mediaDetailsModalShowInit();

});

    function uploadZoneInit(){
            const element = document.querySelector("#upload_media");
            let extraData = {
                '_token':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                ...(all_uploads.allUploadsConf("user_id") && {'user_id': all_uploads.allUploadsConf("user_id")})
            }
            if(element){
                $(element).fileinput({
                    theme: "bs5",
                    browseOnZoneClick: true,
                    uploadUrl: window.all_uploads.allUploadsConf('media_upload_url'),
                    uploadAsync:false,
                    showClose :false,
                    uploadExtraData:extraData,
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
        let extraData = {
            ...(all_uploads.allUploadsConf("user_id") && {'user_id': all_uploads.allUploadsConf("user_id")})
        }
        if(element){
            $.ajax({
                url:  window.all_uploads.allUploadsConf('media_all_uploads_url'),
                type: 'GET',
                data:extraData,
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
            // Pagination options
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


    var mediaDetailsModalShowInit =  function(){
        if(document.querySelector("#remote-uploader-modal")){

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

                var mediaDeleteButton = $('<button/>',
                {
                    text: 'Delete',
                    "class":"btn btn-danger media-delete-button",
                    click: function () { 
                        if (confirm("Media delete confirmation!") == true) {
                            mediaDeleteInit($(deatilsDom).attr('data-details-media-id'))
                          } else {
                            console.log("Cancelled");
                          }
                     }
                });

                $(modalDom).find(".delete-button-holder").html(mediaDeleteButton);


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
       
   
    };
  var mediaDeleteInit = (mediaId)=>{
    $(document).find(`#media-${mediaId}`).remove();
    $.ajax({
        url: window.all_uploads.allUploadsConf('media_delete_url'),
        type: 'POST',
        data:{"mediaId": mediaId},
        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        beforeSend: function() {
            $(".media-delete-button").html("Please wait! ............")
            $(".media-delete-button").attr("disabled", true);
        },
        success: function(response) {
            $('#remote-uploader-modal').modal("hide");
        }
    });
  }
