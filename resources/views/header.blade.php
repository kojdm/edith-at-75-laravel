{{-- <div class="container">
    <div class="row center">
        <p id="main-header" class="yellow-text">Edith @ 75</p>
        <a class="btn-floating btn-large waves-effect waves-teal white z-depth-0"><i class="material-icons" style="color: #ffeb3b;">menu</i></a>
    </div>
    <hr class="header-hr">
</div> --}}

<div class="navbar-fixed">
    <nav class="z-depth-0">
        <div id="nav-contents" class="container">
            <div class="row" style="margin-bottom: 0;">
                <div class="nav-wrapper">
                    <div class="header-wrapper">
                        <a id="main-header" class="brand-logo center">Edith @ 75</a>
                    </div>
                    <div class="right" style="padding-top: 10px;">

                        @if (url()->current() == url('/'))

                            <div class="floating-action-button">
                                <div class="left" id="about-button-wrapper">
                                    <a class=" btn-floating btn-large waves-effect white z-depth-0 tooltipped" data-position="bottom" data-tooltip="About" onclick="$('#aboutModal').modal('open');">
                                        <i class="material-icons make-yellow" id="about-icon">info_outline</i>
                                    </a>                                                          
                                </div>

                                <div class="right">
                                    <a class=" btn-floating btn-large waves-effect white z-depth-0 tooltipped" data-position="bottom" data-tooltip="Upload" onclick="$('#uploadModal').modal('open');">
                                        <i class="material-icons make-yellow" id="upload-icon">file_upload</i>
                                    </a>                          
                                </div>
                            </div>

                        @else

                            <div class="floating-action-button" style="visibility: hidden;">
                                <div class="left" id="about-button-wrapper">
                                    <a class=" btn-floating btn-large waves-effect white z-depth-0 tooltipped" data-position="bottom" data-tooltip="About" onclick="$('#aboutModal').modal('open');">
                                        <i class="material-icons make-yellow" id="about-icon">info_outline</i>
                                    </a>                                                          
                                </div>
    
                                <div class="right">
                                    <a class=" btn-floating btn-large waves-effect white z-depth-0 tooltipped" data-position="bottom" data-tooltip="Upload" onclick="$('#uploadModal').modal('open');">
                                        <i class="material-icons make-yellow" id="upload-icon">file_upload</i>
                                    </a>                          
                                </div>
                            </div>

                        @endif
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <hr style="margin-left: 8vw; margin-right: 8vw;">
        </div>
    </nav>
</div>

@push('scripts')
<script>
    $(document).ready(function(){
        $('.tooltipped').tooltip();
    });
</script>
@endpush