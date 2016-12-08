<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Image;
class Album extends Model
{
    protected $table = 'albums';

    protected $fillable = [
        'name','description','cover_image'
    ];

    public function Photos(){

        return $this->hasMany(Image::class);
    }
}
