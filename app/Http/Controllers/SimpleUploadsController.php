<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class SimpleUploadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = uniqid('', true);
        session(['user_id' => $user_id]);
        return view('simpleupload.simpleuploads');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            \Storage::disk('uploads')->put("processing/{$user_id}/$fileNameToStore", file_get_contents($image));
        }
        return redirect(route("simpleupload.captions"));
    }

    public function captions()
    {
        $user_id = session('user_id');
        $image_paths = \Storage::disk('uploads')->files("processing/{$user_id}/");

        if (empty($image_paths)) {
            return abort(404);
        }

        $data = [
            'image_paths' => $image_paths,
        ];

        return view('simpleupload.captions')->with($data);
    }

    public function storeCaptions(Request $request)
    {
        $user_id = session('user_id');

        if (!isset($request->singer)) {
            return back()->withInput()->with('error', 'Please answer the confirmation question.');
        }

        if (!$this->checkSinger($request->singer)) {
            return back()->withInput()->with('error', 'Sorry! You got the confirmation question wrong.');
        }

        $this->validate($request, [
            'uploader_name' => 'required',
            'uploader_email' => 'required|email',
            'caption' => 'nullable',
            'caption.*' => 'nullable|size:120',
            'singer' => 'required'
        ]);
        
        $uploader_name = $request->input('uploader_name');
        $uploader_email = $request->input('uploader_email');

        $captions = $request->input('caption');

        if (!is_array($captions)) {
            $captions = [$captions];
        }

        foreach ($captions as $name_from_request => $caption) {
            $pathToFile = "uploads/processing/$user_id/" . $name_from_request;

            if (!file_exists($pathToFile)){
                return response()->json(['message' => 'File does not exist.'], 400);
            }

            $extension = strtolower(pathinfo($pathToFile, PATHINFO_EXTENSION));
            $filenameFinal = date('YmdHis') . '_' . uniqid() . '.' . $extension;

            $original_name = explode('&&', $name_from_request)[0];
            $original_size = pathinfo(explode('&&', $name_from_request)[1], PATHINFO_FILENAME);

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

            unlink($pathToFile);
        }

        if (empty(\Storage::disk('uploads')->files("processing/$user_id/"))) {
            \Storage::disk('uploads')->deleteDirectory("processing/$user_id/");
        }

        return redirect(route('home'));
    }

    public function deleteImages()
    {
        $user_id = session('user_id');

        \Storage::disk('uploads')->deleteDirectory("processing/$user_id/");

        return redirect(route('simpleupload.index'));
    }

    public function checkSinger($singer)
    {
        if (strtolower($singer) == 'elvis'){
            return true;
        }
        else {
            return false;
        }
    }
}
