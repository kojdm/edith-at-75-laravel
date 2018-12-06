@extends('master')


@section('content')
{{-- <pre>
    {{ print_r($output) }}
</pre> --}}
<div class="carousel-wrapper">
@if (count($images) > 0)
    <div class="carousel">
        @foreach ($images as $image)
            <a class="carousel-item" href="#">
                <img src="uploads/gallery/{{$image->filename}}" alt='{{$image->caption}}<br><small>FROM {{strtoupper($image->uploader_name)}}</small>' class="myImg" srcset="">
            </a>
        @endforeach
    </div>
@else
    <div class="image-container center">
        <div class="item">
            <img src="placeholder.jpg" alt="Hi! I'm just a placeholder. Upload your own images of Edith using the button on the top-right of this site! <br><small>FROM KOJI</small>" class="myImg" srcset="" style="height: 450px;">
        </div>
    </div>
@endif
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    $('.carousel').carousel({
        numVisible: 3,
        dist: -70,
        shift: 20,
    });
  });
</script>
@endpush