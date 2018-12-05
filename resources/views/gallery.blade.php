<div id="gallery">
    @if (count($images) > 0)
        <div id="macy-container">
            @foreach ($images as $image)
                <div class="image-container">
                    <div class="item">
                        <img src="uploads/gallery/{{$image->filename}}" alt='{{$image->caption}}<br><small>FROM {{strtoupper($image->uploader_name)}}</small>' class="myImg" srcset="">
                    </div>
                </div>
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

{{-- Image Modal --}}
<div id="myModal" class="img-modal">
    <!-- Modal Content (The Image) -->
    <img class="img-modal-content" id="img-display">
    <!-- Modal Caption (Image Text) -->
    <div id="caption"></div>
</div>

@push('scripts')
<script>
    var imageCount = {{count($images)}}
    if (imageCount < 5) {
        var numColumns = imageCount;
    }
    else {
        var numColumns = 5;
    }
    if (($(window).width() <= 600) && imageCount > 2) {
        var numColumns = 3;
    }
    if (imageCount > 0) {
        var macy = Macy({
            container: "#macy-container",
            trueOrder: false,
            waitForImages: true,
            margin: 0,
            columns: numColumns,
        });
    }

    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = $(".myImg");
    var modalImg = $("#img-display");
    var captionText = document.getElementById("caption");
    $(".myImg").click(function() {
        modal.style.display = "block";
        var newSrc = this.src;
        modalImg.attr("src", newSrc);
        captionText.innerHTML = this.alt;
    });

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

</script>
@endpush