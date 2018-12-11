<div class="modal-content">

  <div id="dropzone-form">
    <h5>Leave a photo or two.</h5>
    <hr>
    <form action={{ route('upload.store') }} class="dropzone" id="uploadDropzone" method="multipart/form-data" multiple>
      @csrf
      <div class="dz-message">Drop images here or click to upload</div>
    </form>
  </div>

  <div id="captions-form" style="display: none;">
      <h5>Leave a message or two. Or don't. It's up to you.</h5>
      <hr>
      <div id="preview">
      </div>
  </div>

</div>


<div class="modal-footer">
  <div class="row">
    <div id="preloader" style="padding-top: 15px; margin: 0 20px; display: none;">
        <div class="progress">
            <div class="indeterminate"></div>
        </div>
    </div>
    <div id="modal-buttons">
      <a id="cancel" href="#!" class="btn-flat">Cancel</a>
      <button id="next-button" class="waves-effect waves-light btn-small yellow">Next</button>
      <button id="done-button" class="waves-effect waves-light btn-small yellow" style="display: none;">Done</button>
    </div>
  </div>
</div>

@push('scripts')
<script>
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  Dropzone.autoDiscover = false;
  $(document).ready(function(){
    var goNext = false;

    $("#uploadDropzone").dropzone({
      paramName: 'file',
      maxFilesize: 30,
      clickable: true,
      autoProcessQueue: true,
      uploadMultiple: true,
      parallelUploads: 1,
      addRemoveLinks: true,
      acceptedFiles: "image/*",
      dictCancelUpload: "",
      dictRemoveFile: "Remove",
      init: function(){
        myDropzone = this;

        myDropzone.on("queuecomplete", function(){
          goNext = true;
        });

        var cancelButton = document.querySelector('#cancel');
        cancelButton.addEventListener("click", function(){
          if (confirm("All your uploaded images and captions will be removed. Cancel anyway?")) {
            myDropzone.removeAllFiles();
            $('.modal').modal('close');
            location.reload();
          }
        });

        myDropzone.on("removedfile", function(file){
          $.post({
            url: "deleteimage",
            data: {"name": file.name, "size": file.size, "_token": "{{ csrf_token() }}"},
            dataType: 'json',
            success: function(data){
              console.log(data);
            }
          });
        });
      },
    });

    var nextButton = document.querySelector('#next-button');
    nextButton.addEventListener("click", function(){
      if (goNext == true) {
        $("#dropzone-form").delay(500).hide("slow");
        list_image();
        $('#captions-form').delay(1000).show(0, function(){
          $('#next-button').hide();
          $('#done-button').show();
          goNext = false;

          M.updateTextFields();
          $("textarea").characterCounter();
        });
      }
    });

    $(document).on('click', '.remove_image', function(){
      var name = $(this).attr('id');
      $.post({
        url: "deleteimage",
        data: {"name": name, "_token": "{{ csrf_token() }}"},
        dataType: 'json',
        success: function(data){
          console.log(data);
          var numImages = $(".img-thumbnail").length;
          if ((numImages < 2) && (goNext == false)) {
            $('.modal').modal('close');
            location.reload();
          }
          else {
            list_image();
          }
        }
      });
    });

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

      var singer = prompt("Not so fast, "+uploader_name+"! To verify you really do know Edith, please answer this:\n\nWhat is the FIRST NAME of Edith's all-time favorite singer?");
      $.ajax({
        type: "POST",
        url: "checksinger",
        data: {singer: singer},
        success: function(data){
          console.log(data);
          if (data == 401) {
            alert("Sorry! That ain't it, chief.");
          }
          else {
            alert("Thanks for your contribution!");
            $('#modal-buttons').hide();
            $('#preloader').show();
            uplodi();
          }
        }
      });
    });

    function list_image(){
      var numImages = $(".img-thumbnail").length;
      $.ajax({
        url: "uploadlist",
        success: function(data){
          $('#preview').html(data);
        },
      });
    }

    function uplodi() {
      var uploader_name = $("#uploader_name").val();
      var uploader_email = $("#uploader_email").val();

      var textareas = $("textarea").toArray();
      var counter = 0;
      uploadData(textareas.length);

      function uploadData(length) {        
        var request = {
          "uploader_name": uploader_name,
          "uploader_email": uploader_email,
          "caption_id": textareas[counter].id,
          "caption_value": textareas[counter].value,
          "_token" : "{{ csrf_token() }}",
        };

        $.ajax({
          type: "POST",
          url: "finalupload",
          data: request,
          success: function(data){
            console.log(data);
            counter++;
            if (counter < length) {
              uploadData(length);
            }
            else {
              $('.modal').modal('close');
              location.reload();
            }
          }
        });
      }
    }

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    jQuery.fn.invisible = function() {
        return this.css('visibility', 'hidden');
    };

    jQuery.fn.visible = function() {
        return this.css('visibility', 'visible');
    };

  });
</script>
@endpush