<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public $fillable = ['name'];

    public function tokens(){
        return $this->belongsToMany(Token::class, 'boolean_index')->withPivot('times');;
    }
}
