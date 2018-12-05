@extends('master')

@section('content')
    {{-- <div id="upload-button" class="fixed-action-btn">
        <a class="btn-floating btn-large waves-effect waves-light yellow z-depth-3 tooltipped" data-position="left" data-tooltip="Click to upload photos" onclick="$('#uploadModal').modal('open');"><i class="material-icons">add</i></a>
    </div> --}}

    <div id="uploadModal" class="modal modal-fixed-footer">
        @include('upload')
    </div>

    <div id="aboutModal" class="modal modal-fixed-footer">
        @include('about')
    </div>

    <div class="container">
        @include('gallery')
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('#uploadModal').modal({
            dismissible: false,
        });
        $('#aboutModal').modal();
    });
</script>
@endpush