@extends('master')

@section('content')
<div class="container" style="margin-top: 50px;">
    <h4>Leave a photo or two.</h4>
    <br>

    <form method="POST" action={{ route('simpleupload.store') }} enctype="multipart/form-data">
        @csrf
        <div class="file-field input-field">
        <div class="btn">
            <span>File</span>
            <input type="file" name="file[]" multiple>
        </div>
        <div class="file-path-wrapper">
            <input class="file-path validate" type="text" placeholder="Click here to upload your photos.">
        </div>
        </div>

        <br>
        <br>

        <div class="right">
            <a href="{{url('/')}}" class="btn-flat">Cancel</a>
            <button type="submit" class="btn">Next</button>
        </div>
    </form>

</div>
@endsection