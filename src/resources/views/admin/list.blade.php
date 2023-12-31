@inject('carbon', 'Carbon\Carbon')
<div class="remote-uploader-body">
		@foreach($uploadedMedias as $thread)
			@forelse ($thread->getMedia('admin_collection') as $media)
				@if($media->hasGeneratedConversion('thumb'))
					<div class="remote-uploader-item picture-item">
						<a href=" {{$media->getUrl()}}" data-bs-toggle="modal" data-bs-target="#remote-uploader-modal"
						data-details-uploaded-at="{{$carbon::parse($media->created_at)->format('F d Y')}}"
						data-details-file-name="{{$media->file_name}}"
						data-details-file-type="{{$media->mime_type}}"
						data-details-file-size="{{$media->human_readable_size}}"
						data-details-file-url="{{$media->getFullUrl()}}"
						>
							<img src=" {{$media->getUrl('thumb')}}" 
							alt="{{$media->getUrl()}}"
							class="img-fluid" />
							
						</a>
					</div>
				@else
					<div class="remote-uploader-item picture-item" data-groups='["uploaded_medias"]'>
						<a href=" {{$media->getUrl()}}" data-bs-toggle="modal" data-bs-target="#remote-uploader-modal">
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
    <div class="custom-pagination d-flex justify-content-center">
    {{ $uploadedMedias->withQueryString()->links('AllUploads::admin.custom-paginator-cursor') }} 
    </div>
</div>
	