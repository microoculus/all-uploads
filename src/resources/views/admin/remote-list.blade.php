<div class="remote-uploader">
	<div class="remote-uploader-header">
		<div class="header-left">
			<ul class="nav nav-tabs ru-uploads-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active list-of-ru-uploads" data-bs-toggle="tab" href="#list-of-ru-uploads" role="tab" aria-selected="true">Uploaded medias</a>
				</li>
				<li class="nav-item">
					<a class="nav-link zone-of-ru-uploads" data-bs-toggle="tab" href="#zone-of-ru-uploads" role="tab" aria-selected="false">Upload</a>
				</li>
			</ul>
		</div>
		<div class="header-right">
			<div class="btn-group ms-1 media-sort-container">
                <button type="button" class="btn btn-de-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Sort</button>
                <div class="dropdown-menu media-sort" style="" >
                    <a class="dropdown-item " data-sortingOption="ASC" href="#" >Oldest to Newest</a>
					<a class="dropdown-item "  data-sortingOption="DESC" href="#" >Newest to Oldest</a>
                </div>
            </div>
		</div>
	</div>


<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane p-3 active remote-uploader-tab" id="list-of-ru-uploads" role="list-of-ru-uploads">
		<div id="grid" class="remote-uploader-body">
		@foreach($uploadedMedias as $thread)
			@forelse ($thread->getMedia('admin_collection') as $media)
				@if($media->hasGeneratedConversion('thumb'))
					<div class="remote-uploader-item picture-item" data-groups='["uploaded_medias"]'>
						<a href=" {{$media->getUrl()}}" class="lightbox">
							<img src=" {{$media->getUrl('thumb')}}" 
							alt="{{$media->getUrl()}}"
							class="img-fluid" 
							data-mediaid="{{$media->id}}" />
							
						</a>
					</div>
				@else
					<div class="remote-uploader-item picture-item" data-groups='["uploaded_medias"]'>
						<a href=" {{$media->getUrl()}}" class="lightbox">
							<img src=" {{URL::asset('assets/images/thumbnail-not-available.png')}}" alt="" loading="lazy" class="img-fluid" data-mediaid="{{$media->id}}" />
						</a>
					</div>
				@endif
			@empty
				<div class="remote-uploader-item picture-item" data-groups='["uploaded_medias"]' style="pointer-events: none;opacity: 0.4;">
					<a href="{{ URL::asset('assets/images/image-not-found.png') }}" class="lightbox">
						<img src="{{ URL::asset('assets/images/image-not-found.png') }}" 
						alt="Image not found"
						class="img-fluid" />
						
					</a>
				</div>
			@endforelse
		@endforeach
		</div>
		<div class="remote-uploader-footer">
			<div class="custom-cursor-pagination d-flex justify-content-center">
			{{ $uploadedMedias->withQueryString()->links('AllUploads::admin.custom-paginator-cursor') }}
			</div>
		</div>
    </div>
    <div class="tab-pane p-3" id="zone-of-ru-uploads" role="zone-of-ru-uploads">
        
    </div>
   
</div>
</div>