    if(typeof window.all_uploads.allUploadsConf == undefined){
        throw new Error("all up loads script error! Global function not defined");
    }
   
   (function ( $ ) {
	$.fn.remoteUploader = function(options) {

        var settings = $.extend(true,{
            multipleMedia:false,
            initilaPreview:false,
            mediaPreviewConfig:[],
            mediaPreviewDeleteUrl:null,
            mediaRequired:false,
        }, options );

        var ajaxLoadUrl = window.all_uploads.allUploadsConf('media_remot_all_uploads_url');
        var ajaxUploaddUrl =  window.all_uploads.allUploadsConf('media_upload_ulr');
        var initilaPreview = settings.initilaPreview;
        var mediaPreviewConfig = settings.mediaPreviewConfig;
        var threadId = null;
       
        return this.each(function() {
            var elem = $(this);
     
            // if(settings.multipleMedia){
            //     $(elem).attr('name',   $(elem).attr('name')+"[]");
            //     $(elem).addClass('ru-multiple');
            // }


            if(typeof elem.data('multiple_media') !== 'undefined'){
                settings.multipleMedia = true;
                $(elem).attr('name',   $(elem).attr('name')+"[]");
                $(elem).addClass('ru-multiple');
            }

            formResetInit(elem);
            var createBrowsAndPreviewDom = function(){

                let browseButtonConatiner =  $('<div />', {
                    id: '',
                   class:"ru-input-and-preview-dom"
                 });

                let browseButton = $('<div />', {
                    id: '',
                   class:"ru-custom-file"
                 });
                 $(browseButton).on('click', function(event){
                    event.preventDefault();
                    modalBoxHandler();
                });

                 $(browseButton).html('<div class="ru-custom-file-text">No file selected.</div><div class="ru-custom-file-btn">Browse...</div>')

                 let ruPreviewimageDiv  = $('<div />', {
                    id: '',
                   class:"ru-preview-img"
                 });

                
                 $(browseButton).appendTo(browseButtonConatiner);
                 $(ruPreviewimageDiv).appendTo(browseButtonConatiner);

                return browseButtonConatiner;
            }

            $(this).wrap('<div class="ru-dynamic"></div>');
            var dynamicDom = $('<div />', {
                id: '',
               class:""
             });
             $(dynamicDom).insertAfter( $(this));
             let necessaryDoms = createBrowsAndPreviewDom();
             $(dynamicDom).html(necessaryDoms)


            if(typeof elem.data('multiple_media') !== 'undefined' && initilaPreview && mediaPreviewConfig.length > 1  ){
                initilaPrviewMultipleMediaInit(elem)
            }else if(typeof elem.data('multiple_media') === 'undefined' && initilaPreview && mediaPreviewConfig.length > 0){

                initilaPrviewSingleMediaInit(elem, settings);
            }

            var modalBoxHandler = function(){
                var modal = document.createElement('div');
                    modal.classList.add('modal', 'fade');
                    modal.setAttribute('id', 'remote-file-content-modal');
                    modal.setAttribute('tabindex', '-1');
                    modal.setAttribute('role', 'dialog');
                    modal.setAttribute('aria-labelledby', 'remote-file-content-modal');
                    modal.setAttribute('aria-hidden', 'true');
                    modal.innerHTML =
                            '<div class="modal-dialog modal-xl"  role="document">' +
                            '<div class="modal-content">'+
                           ' <div class="modal-header">'+
                            '<h6 class="modal-title" id="exampleModalFullscreenLabel">All Media</h6>'+
                           ' <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
                            '</div>'+
                            '<div class="modal-body" id="remote-file-content-modal-body"></div>'+
                            ' <div class="modal-footer">'+
                            '<button type="button" class="btn btn-primary use-this-media-button" data-bs-dismiss="modal">Use this media</button>'+
                            '</div>'+
                            '</div>' +
                            '</div>';
                    document.body.appendChild(modal);

                    $(modal).modal("show");
                    $('.use-this-media-button').hide();
                    $(modal).on('hidden.bs.modal', function (e) {
                        $("#remote-file-content-modal-body").remove();
                      })
                    ajaxRequestHandler();
                   
            }


            var ajaxRequestHandler = function(){
                var element = $("#remote-file-content-modal-body");
                $.ajax({
                    url:  ajaxLoadUrl,
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    beforeSend: function() {
                        // setting a timeout
                        $(element).html('loading...');
                    },
                    success: function(response) {
                        $(element).empty().html(response);
                        paginationRequestHndler();
                        setImageClickInit();
                        dropZoneTabInit();
                        sortingInit();
                        activeTabOptimisation();
                      
                    }
                });
            }

            var paginationRequestHndler = function(){
             
                if(document.querySelector(".custom-cursor-pagination ul.pagination li.page-item")){
                    $("li.page-item").each(function(){
                       if(!$(this).is(':disabled')) {
                        $(this).on("click", 'a', function(event){
                            event.preventDefault();
                            ajaxLoadUrl = $(this).attr('href');
                            ajaxRequestHandler();
                           
                        });
                       }
                    });
                }
            }

            var setSelectorIcon = function(){

            }

            var setImageClickInit = function(){
                
                $("#remote-file-content-modal-body").find('img').each(function(){
                    $(this).on("click", function(event){
                        event.preventDefault();
                        let ruDynamic = $(elem).closest('.ru-dynamic');
                        let dataUuid = Math.random().toString(16).slice(2);
                        let closestDiv = $(this).closest("div");
                        if(typeof elem.data('multiple_media') !== 'undefined'){
                            let ruMultipleFirstInputs = $(ruDynamic).find("input.ru-multiple:first");
                            
                            if(ruMultipleFirstInputs.hasAttr("data-uuid")){
                               
                                let allAttributes = ruMultipleFirstInputs.prop("attributes");
                                let neRuMultipleInput =   $('<input />');
                                  $.each(allAttributes, function() {
                                    neRuMultipleInput.attr(this.name, this.value);
                                });
                                $(neRuMultipleInput).val($(this).data('mediaid'));
                                $(neRuMultipleInput).attr("data-uuid", dataUuid);
                                let ruMultiplelastInputs =  $(ruDynamic).find("input.ru-multiple:last");
                                $(neRuMultipleInput).insertAfter( $(ruMultiplelastInputs));
                                $('.use-this-media-button').show();
                                $(closestDiv).addClass("media-selected");
                            }else{

                                    $(ruMultipleFirstInputs).val($(this).data('mediaid'));
                                    $(ruMultipleFirstInputs).attr("data-uuid", dataUuid);
                                    $(closestDiv).addClass("media-selected");
                                    
                            }
                            
                        }else{
                            $(elem).val($(this).data('mediaid'));
                            $(elem).attr("data-uuid", dataUuid);
                            ruDynamic.find(".ru-preview-img").empty();
                            $(".media-selected").removeClass("media-selected");
                            $('.use-this-media-button').show();
                            $(closestDiv).addClass("media-selected");

                        }

                        let imagePreview=  $(ruDynamic).find('.ru-preview-img');
                        let img = $('<img />', { 
                         id: '',
                         src: $(this).attr('src'),
                         alt: 'Image',
                         class:"mt-2 mb-2 ru-selected-media",
                         width:"100px",
                         height:"100px"
                       });
                       let span = $('<span />', { 
                         id: '',
                         class:"float-right fs-5 ru-cursor-pointer ru-remove-media",
                       });
                         span.html('<i class="fa-solid fa-trash-can"></i> ')


                        let imageBlockDiv = $('<div />', { 
                            id: '',
                            class:"ru-image-block",
                          });

                        //   imageBlockDiv.css({"float": "left"});
                          imageBlockDiv.attr('data-uuid', dataUuid);
                          imageBlockDiv.appendTo($(imagePreview));

                        img.appendTo($(imageBlockDiv));
                        span.appendTo($(imageBlockDiv));
                        removeMediaInit();

                    })
                });
            }

            var handleNameMultipleMediaOption = function(selector){
                $(selector).attr('name',  $(selector).attr('name')+"[]")
            }
          

           var removeMediaInit = function(){
            $(document).off("click").on("click", ".ru-remove-media", function(){
                let imageBlockDiv = $(this).closest('.ru-image-block');
                let dataUuid = $(imageBlockDiv).attr('data-uuid');
                let ruDynamic = $(this).closest('.ru-dynamic');
                let allRelatedInputs = $(ruDynamic).find("input[data-uuid]");
                if(allRelatedInputs.length > 1 && settings.multipleMedia)
                {
                    let slectedInput = $(ruDynamic).find(`[data-uuid='${dataUuid}']`);
                    $(slectedInput).remove();
                }else if(allRelatedInputs.length == 1 && settings.multipleMedia){
                    let slectedInput = $(ruDynamic).find(`[data-uuid='${dataUuid}']`)
                   $(slectedInput).val("");
                   $(slectedInput).removeAttr('data-uuid');
                }else if(allRelatedInputs.length == 1 && !settings.multipleMedia){
                    let slectedInput = $(ruDynamic).find(`[data-uuid='${dataUuid}']`)
                   $(slectedInput).val("");
                   $(slectedInput).removeAttr('data-uuid');
                }
                // let media = $(ruDynamic).find('.ru-selected-media');
                $(imageBlockDiv).remove();
                $(this).remove();
              });

           }

           var dropZoneTabInit = function(){
                if(document.querySelector("#zone-of-ru-uploads")){
                    let dropZoneInput = $('<input />', {
                        id: 'ru-dropzone-upload-media',
                        type:"file",
                        name:"upload_media[]",
                       class:"",
                       multiple:true
                     });
                     dropZoneInput.appendTo($("#zone-of-ru-uploads"));
                     $(dropZoneInput).fileinput({
                        browseOnZoneClick: true,
                        uploadUrl: ajaxUploaddUrl,
                        uploadAsync:false,
                        showClose :false,
                        uploadExtraData:{
                            '_token':document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            
                        },
                    });

                    dropZoneInput.on('filebatchuploadsuccess', function(event, data) {
                        ajaxRequestHandler();
                    });
                    // dropZoneInput.on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
                    //     console.log('File batch upload complete', preview, config, tags, extraData);
                    //     ajaxRequestHandler();
    
                    // });
                }
           }


           var sortingInit = function(){
                if(document.querySelector(".media-sort")){
                    $(".media-sort").find("[data-sortingOption]").each(function(){
                        $(this).off("click").on("click",function(){

                            ajaxLoadUrl = new URL(ajaxLoadUrl);
                            ajaxLoadUrl.searchParams.set("sorting", $(this).attr("data-sortingOption")); 
                            ajaxLoadUrl = ajaxLoadUrl.href;
                            ajaxRequestHandler();
                        });
                    });
                }
           }
          
           var activeTabOptimisation =  function(){
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                let target = $(e.target);
               
                if($(target).hasClass('list-of-ru-uploads')){
                    $('.media-sort-container').show();
                    if(document.querySelector(".media-selected")){
                        $('.use-this-media-button').show();
                    }
                   
                }

                if($(target).hasClass('zone-of-ru-uploads')){
                   
                    $('.media-sort-container').hide();
                    $('.use-this-media-button').hide();
                }
              });
           };
           
         
     
        });

       
        
       
	};


    $.fn.remoteUploaderValidate = function(options) {
        var settings = $.extend(true,{
            mediaRequired:true,
            errorMessage:"Image required",
            classlist: "float-right text-danger error-span"
        }, options );

        var div_ru_dynamic = null;
        var validate_status = true;
        var form = null

        this.each(function() {
            var elem = $(this);
            if($(this).is('form')){
                form = $(this);
                div_ru_dynamic = $(elem).find('div.ru-dynamic');
            }else{

                 form = $(elem).closest("form");
                div_ru_dynamic = $(elem).closest('div.ru-dynamic');
            }
            

            $(div_ru_dynamic).find('input').each(function(){
                if(!$(this).hasAttr("data-uuid")){
                    validate_status = false ;
                }
            });
            if(!validate_status){
           
                let custom_file = $(div_ru_dynamic).find(".ru-custom-file");
                let span = $('<span />', { 
                    id: '',
                    class:settings.classlist,
                    html:settings.errorMessage,
                    role:"alert"
                  });
                  $( span ).attr('data-ru-error', 'error')
                  $(custom_file).next().remove("span");
                  $( span ).insertAfter( custom_file );
            }else if(validate_status){
               let eror_span = $(div_ru_dynamic).find("span[data-ru-error]");
               $(eror_span).remove();
            }
    
        });

      
        return validate_status;

    };

}(jQuery));



   var formResetInit = function(elem){

    let form = $(elem).closest("form");
    
    $(form).on('reset', function(event) {
        $(form).find("div.ru-preview-img").empty();

        let div_ru_dynamic = $(form).find('div.ru-dynamic')
       
        $( div_ru_dynamic ).each(function( index ) {
             $(this).find('input[data-uuid]:not(:first)').remove();
             $(this).find("input[data-uuid]").first().val("" ).removeAttr('data-uuid');
            
          });
    });
   }


   var mediaRequiredValidationInit = function(elem){
    let form = $(elem).closest("form");
    $(form).on('submit', function(event) {
        event.preventDefault();
        throw '';  
    })
   }

   
   var initilaPrviewMultipleMediaInit = function(){
        let ruDynamic = $(elem).closest('.ru-dynamic');
        let dataUuid = Math.random().toString(16).slice(2);
       
    }
    var initilaPrviewSingleMediaInit = function(elem, settings){

        let mediaPreviewConfig = settings.mediaPreviewConfig;
        
        let ruDynamic = $(elem).closest('.ru-dynamic');
        let dataUuid = Math.random().toString(16).slice(2);
        let previewData = mediaPreviewConfig[0];
        $(elem).val(previewData.key);
        $(elem).attr("data-uuid", dataUuid);
        ruDynamic.find(".ru-preview-img").empty();

        let imagePreview=  $(ruDynamic).find('.ru-preview-img');
                        let img = $('<img />', { 
                         id: '',
                         src:previewData.url,
                         alt: 'Image',
                         class:"mt-2 mb-2 ru-selected-media",
                         width:"100px",
                         height:"100px"
                       });

                     


                        let imageBlockDiv = $('<div />', { 
                            id: '',
                            class:"ru-image-block",
                          });

                          imageBlockDiv.css({"float": "left"});
                          imageBlockDiv.attr('data-uuid', dataUuid);
                          imageBlockDiv.appendTo($(imagePreview));
                        img.appendTo($(imageBlockDiv));


                        // 
                        if(settings.mediaPreviewDeleteUrl !== null){

                            let span = $('<span />', { 
                            id: '',
                            class:"float-right fs-5 ru-cursor-pointer ru-remove-media",
                            });
                            span.html('<i class="fa-solid fa-trash-can"></i>')
                            span.appendTo($(imageBlockDiv));

                        // removeMediaInit();
                        }
                        
    }


$.fn.hasAttr = function(name) {
    
    let  attr =  this.attr(name) ;
      if (typeof attr !== 'undefined' && attr !== false) {
         return true;
      }else{
        return false
      }
   };