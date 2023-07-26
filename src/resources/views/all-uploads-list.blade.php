
@inject('carbon', 'Carbon\Carbon')
<div class="all-uploads-block">
	<div class="remote-uploader-header mt-4">
		<div class="header-left">
			<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#media-upload-ful-screen-modal"><i class="bi bi-upload"></i> Upload</button>
		</div>
		<div class="header-right">
			<div class="btn-group ms-1">
                <button type="button" class="btn btn-de-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Sort<i class="mdi mdi-chevron-down ms-1"></i></button>
                <div class="dropdown-menu media-sort" style="">
                    <a class="dropdown-item" data-sortingOption="ASC" href="#">Oldest to Newest</a>
					<a class="dropdown-item"  data-sortingOption="DESC" href="#">Newest to Oldest</a>
                </div>
            </div>
		</div>

	</div>
	
        <div id="all-uploaded-media">
        
        </div>
	
	
</div>

  <!-- Modal 1 content start -->
  <div class="modal fade" id="media-upload-ful-screen-modal" tabindex="-1" aria-labelledby="media-upload-ful-screen-modal" aria-hidden="true" role="document">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="exampleModalFullscreenLabel">New Media</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                   <form>
                                        <input id="upload_media" name="upload_media[]" multiple type="file">
                                   </form>
                                </div>
                                
                            </div>
                        </div>
                    </div>
    <!-- Modal 1 content end -->

<!-- Modal 2 -->
<div class="modal fade" id="remote-uploader-modal" tabindex="-1" aria-labelledby="remote-uploader-modal" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				<div class="remote-uploader-modal-media">
					<img class="feature-image" src="" alt=""/>
				</div>
				<div class="remote-uploader-modal-media-details">
					<p><b>Uploaded at:</b> <span class="uploaded-at"> </span> <br/>
					<b>File name:</b> <span  class="file-name"></span><br/>
					<b>File type:</b> <span  class="file-type"></span><br/>
					<b>File size:</b><span  class="file-size"></span><br/>

					<label class="form-label">URL</label>
					<div class="input-group mb-3">
					  <input type="text" class="form-control file-url" name="" id="" value="" readonly>
					<a href="javascript:void(0);" class="btn btn-de-secondary url-copy"><i class="ti ti-copy menu-icon"></i></a>
					</div>
					
				</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2 -->

