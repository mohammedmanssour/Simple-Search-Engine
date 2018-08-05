<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public $fillable = ['word'];

    public function documents(){
        return $this->belongsToMany(Document::class, 'boolean_index')->withPivot('times');
    }
}
