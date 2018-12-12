<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class UploadsController extends Controller
{
    public function store(Request $request)
    {
        $user_id = session('user_id');

        $this->validate($request, [
            'file' => 'required',
            'file.*' => 'image|max:10240'
        ]);

        $images = $request->file('file');

        if (!is_array($images)) {
            $images = [$images];
        }

        foreach ($images as $image) {
            // Get filename with extension
            $filenameWithExt = $image->getClientOriginalName();
            // Get file size
            $filesize = $image->getSize();
            // Get just ext
            $extension = strtolower($image->getClientOriginalExtension());
            // Filename to store
            $fileNameToStore = $filenameWithExt.'&&'.$filesize.'.'.$extension;
            // Upload file
            // $path = $image->storeAs("public/uploads/{$user_id}", $fileNameToStore);

            \Storage::disk('uploads')->put("processing/{$user_id}/$fileNameToStore", file_get_contents($image));

            // $img = Image::make("uploads/$user_id/$fileNameToStore")->orientate();
            // $img->resize(700, 700, function ($constraint) {
            //     $constraint->aspectRatio();
            // });
            // $img->save("uploads/$user_id/$fileNameToStore");
        }
    }

    public function finalUpload(Request $request)
    {
        $user_id = session('user_id');

        $this->validate($request, [
            'uploader_name' => 'required',
            'uploader_email' => 'required|email'
        ]);

        $uploader_name = $request->uploader_name;
        $uploader_email = $request->uploader_email;

        $name_from_request = explode('&&&', $request->caption_id)[1];
        $caption = "";
        if(isset($request->caption_value)){
            $caption = $request->caption_value;
        }

        // $pathToFile = "storage/uploads/$user_id/" . $name_from_request;
        $pathToFile = "uploads/processing/$user_id/" . $name_from_request;

        if (!file_exists($pathToFile)){
            return response()->json(['message' => 'File does not exist.'], 400);
        }

        $extension = strtolower(pathinfo($pathToFile, PATHINFO_EXTENSION));
        $filenameFinal = date('YmdHis') . '_' . uniqid() . '.' . $extension;

        $original_name = explode('&&', $name_from_request)[0];
        $original_size = pathinfo(explode('&&', $name_from_request)[1], PATHINFO_FILENAME);

        // return [$name_from_request, $caption, $pathToFile, $extension, $filenameFinal, $original_name, $original_size];

        \DB::table('uploads')->insert([
            'filename' => $filenameFinal,
            'caption' => $caption,
            'original_name' => $original_name,
            'original_size' => $original_size,
            'session_id' => \Session::getId(),
            'uploader_name' => $uploader_name,
            'uploader_email' => $uploader_email,
        ]);

        $img = Image::make("uploads/processing/$user_id/$name_from_request")->orientate();
        $img->resize(700, 700, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save("uploads/gallery/$filenameFinal");

        // \Storage::disk('uploads')->delete("processing/$user_id/$name_from_request");
        unlink($pathToFile);

        if(empty(\Storage::disk('uploads')->files("processing/$user_id/"))) {
            \Storage::disk('uploads')->deleteDirectory("processing/$user_id/");
        }

        return response()->json(['message' => 'File successfully moved.'], 200);

        // // if (\Storage::move("public/uploads/$user_id/$name_from_request", "public/gallery/$filenameFinal")) {

        // if (\Storage::disk('uploads')->move("$user_id/$name_from_request", "gallery/$filenameFinal")) {
        //     // if(empty(\Storage::files("public/uploads/$user_id/"))) {

        //     if(empty(\Storage::disk('uploads')->files("$user_id/"))) {

        //         // \Storage::deleteDirectory("public/uploads/$user_id/");

        //         \Storage::disk('uploads')->deleteDirectory("$user_id/");
        //     }

        //     return response()->json(['message' => 'File successfully moved.'], 200);
        // }
        // else {
        //     return "wtf man";
        // }
    }

    public function listImages()
    {
        $user_id = session('user_id');

        $result = array();

        // $folder_name = "storage/uploads/$user_id/";
        $folder_name = "uploads/processing/$user_id/";

        if (!file_exists($folder_name)) {
            return redirect('/');
        }

        $files = preg_grep('~\.(jpeg|jpg|png|gif|bmp|tif)$~', scandir($folder_name));

        if($files !== false)
        {
            $output = '<div class="grid-container">';
            $output .= '
            <div id="uploader-container">
                <div class="row">
                    <div class="col s4">
                        <label>Name</label>
                    </div>
                    <div class="col s8">
                        <input type="text" placeholder="This is required." id="uploader_name">  
                    </div>               
                </div>
                <div class="row">
                    <div class="col s4">
                        <label>E-mail</label>                        
                    </div>
                    <div class="col s8">
                        <input type="email" placeholder="This too." id="uploader_email" class="validate"> 
                    </div>                 
                </div>
            </div>
            ';
            foreach($files as $file)
            {
                if('.' !=  $file && '..' != $file)
                {
                    $output .= '
                    <div class="row">
                        <div class="col s4 thumbnail-wrapper">
                            <div class="modal-grid-image">
                                <img src="'.$folder_name.$file.'" class="img-thumbnail">
                            </div>
                        </div>
                        <div class="input-field col s8 textarea-wrapper">
                            <textarea id="captionfor&&&'.$file.'" class="materialize-textarea" data-length="120" placeholder="Leave your caption/message here."></textarea>
                            <button type="button" class="waves-effect waves-yellow btn-flat btn-small remove_image" id="'.$file.'">Remove</button>
                        </div>
                    </div>
                    ';
                }
            }
            $output .= '</div>';
        }
        
        echo $output;
    }

    public function deleteImage(Request $request)
    {
        $user_id = session('user_id');
        
        if (isset($request->size)) {
            $original_name = $request->name;
            $original_size = $request->size;
            $extension =  pathinfo($original_name, PATHINFO_EXTENSION);

            $filename = $original_name . '&&' . $original_size . '.' . $extension;
        }
        else {
            $filename = $request->name;
        }

        // $pathToFile = "storage/uploads/$user_id/" . $filename;
        $pathToFile = "uploads/processing/$user_id/" . $filename;

        if (unlink($pathToFile)) {
            // if(empty(\Storage::files("public/uploads/$user_id/"))) {

            if (empty(\Storage::disk('uploads')->files("processing/$user_id/"))){
                // \Storage::deleteDirectory("public/uploads/$user_id/");
                
                \Storage::disk('uploads')->deleteDirectory("processing/$user_id/");
            }
            return response()->json(['message' => 'File successfully deleted'], 200);
        }
    }

    public function checkSinger(Request $request)
    {
        if (isset($request->singer)) {
            if (strtolower($request->singer) == 'elvis'){
                return 200;
            }
            else {
                return 401;
            }
        }
        else {
            return 401;
        }
    }
}
