@extends('master')

@section('content')
<link href="https://fonts.googleapis.com/css?family=Lato|Playfair+Display:400,400i" rel="stylesheet">
<link rel="stylesheet" href="css/custom.css">

<div class="container" style="margin-top: 50px;">
    <h4>Leave a message or two. Or don't. It's up to you.</h4>
    <br>

    @if(session('error'))
        <div class="card-panel red" style="color: white;">
            {{session('error')}}
        </div>
    @endif

    <form id="captionsForm" action={{ route('simpleupload.storeCaptions') }} method="POST">
        @csrf
        <div id="uploader-container">
            <div class="row">
                <div class="col s3">
                    <label>Name</label>
                </div>
                <div class="col s9">
                    <input type="text" placeholder="This is required." id="uploader_name" name="uploader_name" value="{{old('uploader_name')}}">  
                </div>               
            </div>
            <div class="row">
                <div class="col s3">
                    <label>E-mail</label>                        
                </div>
                <div class="col s9">
                    <input type="email" placeholder="This too." id="uploader_email" class="validate" name="uploader_email" value="{{old('uploader_email')}}"> 
                </div>                 
            </div>
        </div>
        

        @foreach ($image_paths as $image_path)
        <div class="row">
            <div class="col s3 thumbnail-wrapper">
                <div class="modal-grid-image" style="height: 150px; width: 150px;">
                    <img src="{{ URL::asset("uploads/$image_path") }}" class="img-thumbnail" style="max-width: 200px;">
                </div>
            </div>
            <div class="input-field col s9 textarea-wrapper">
                <textarea name="caption[{{pathinfo($image_path, PATHINFO_BASENAME)}}]" class="materialize-textarea" data-length="120" placeholder="Leave your caption/message here."></textarea>
            </div>
        </div>
        @endforeach


        <div class="row">
            <div class="card-panel yellow">
                Confirmation Question:<br>
                What is the FIRST NAME of Edith's all-time favorite singer?
                <input type="text" name="singer" id="singer">
            </div>
        </div>
    </form>

    <br>
    <br>

    <div class="right">
        <div class="row">
            <div class="left" style="margin-right: 15px;">
                <form action="{{ route('simpleupload.deleteImages') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-flat">Back</button> 
                </form>
            </div>
            <div class="right">
                <button type="submit" class="btn" form="captionsForm" id="done-button">Done</button>
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $("textarea").characterCounter();

        var doneButton =  document.querySelector('#done-button');
        doneButton.addEventListener("click", function(){
            var uploader_name = $("#uploader_name").val();
            var uploader_email = $("#uploader_email").val();

            if ((uploader_name.length < 1) || (uploader_email.length < 1)) {
                alert("Both your name and email are required.")
                return;
            }

            if (!validateEmail(uploader_email)) {
                alert("Please enter a valid e-mail address.");
                return;
            }

            var textareas = $("textarea").toArray();
            var MAXLENGTH = 120;
            for (var i = 0; i < textareas.length; i++) {
                var caption = textareas[i].value;
                if (caption.length > MAXLENGTH) {
                alert("You went a little overboard with your message(s) there, "+uploader_name+".");
                return;
                }
            }
        });

        function validateEmail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
    });
</script>
@endpush