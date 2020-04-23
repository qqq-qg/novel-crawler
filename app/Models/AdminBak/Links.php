<?php

namespace App\Models\AdminBak;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    protected $fillable = [
        'title',
        'linkurl',
        'listorder',
        'status',
    ];
}
