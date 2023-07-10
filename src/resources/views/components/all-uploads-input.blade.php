@props([
    'id'=>"",
    'name' => 'file_id',
    'multiple_media' => false
])
<input 
    type="hidden" 
    {{$attributes->merge(['class' => 'remote-uploader'])}} 
    id="{{$id}}" 
    name="{{$name}}" 
    @if($multiple_media)
    data-multiple_media= "true"
    @endif
    />

