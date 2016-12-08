<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Album;

use App\Image;

use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Validator;
class ImagesController extends Controller
{
    public function getForm($id)
    {
        $album = Album::find($id);
        return view('addimage')
        ->with('album',$album);
    }

    public function postAdd( Request $request )
    {
        $rules = array(

        'album_id' => 'required|numeric|exists:albums,id',
        'image'=>'required|image'

        );
        
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){

        return Redirect::route('add_image',array('id' =>$request->get('album_id')))
        ->withErrors($validator)
        ->withInput();
        }

        $file = $request->file('image');
        $random_name = str_random(8);
        $destinationPath = 'albums/';
        $extension = $file->getClientOriginalExtension();
        $filename=$random_name.'_album_image.'.$extension;
        $uploadSuccess = $request->file('image')->move($destinationPath, $filename);
        Image::create(array(
        'description' => $request->get('description'),
        'image' => $filename,
        'album_id'=> $request->get('album_id')
        ));

        return Redirect::route('show_album',array('id'=>$request->get('album_id')));
    }
    public function getDelete($id)
    {
        $image = Image::find($id);
        $image->delete();
        return Redirect::route('show_album',array('id'=>$image->album_id));
    }
    public function postMove( Request $request )
    {
    $rules =[
        'new_album' => 'required|numeric|exists:albums,id',
        'photo'=>'required|numeric|exists:images,id'
    ];

    $validator = Validator::make($request->all(), $rules);
    if($validator->fails()){

        return Redirect::route('index');
    }
    $image = Image::find($request->get('photo'));
    $image->album_id = $request->get('new_album');
    $image->save();
    return Redirect::route('show_album',array('id'=>$request->get('new_album')));
    }
}
