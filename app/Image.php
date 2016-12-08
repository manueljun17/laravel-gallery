<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
  
    protected $fillable = [
        'album_id','description','image'
    ];

    public function Album(){
        return $this->belongsTo('albums');
    }
}
