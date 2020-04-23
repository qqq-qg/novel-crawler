<?php

namespace App\Models\Admin;

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
