<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Tags extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'task_id'
    ];

}
