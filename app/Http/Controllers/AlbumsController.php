<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use File;

use App\Album;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Redirect;
class AlbumsController extends Controller
{
  
    public function getList()
    {
        $albums = Album::with('Photos')->get();
        return view('index')
        ->with('albums',$albums);
    }
    public function getAlbum($id)
    {
        $data['album'] = Album::with('Photos')->find($id);
        $data['albums'] = Album::all();
        return view('album')->with($data);
    }
    public function getForm()
    {
        return view('createalbum');
    }
    public function postCreate( Request $request )
    {
        $rules = [
            'name' => 'required',
            'cover_image'=>'required|image'
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){

        return Redirect::route('create_album_form')
        ->withErrors($validator)
        ->withInput();
        }

        $file = $request->file('cover_image');
        $random_name = str_random(8);
        $destinationPath = 'albums/';
        $extension = $file->getClientOriginalExtension();
        $filename=$random_name.'_cover.'.$extension;
        $uploadSuccess = $request->file('cover_image')
        ->move($destinationPath, $filename);
        $album = Album::create([
        'name' => $request->get('name'),
        'description' => $request->get('description'),
        'cover_image' => $filename
        ]);

        return Redirect::route('show_album',array('id'=>$album->id));
    }

    public function getDelete($id)
    {
        $album = Album::find($id);
        $image = 'albums/' . $album->cover_image;
        File::delete($image);
        $album->delete();
        return Redirect::route('index');
    }
}
