<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'filename', 'user_id'
    ];

    public static function saveImage(Request $request)
    {
        $file = $request->file('image');
        if (is_null($file)) {
            return "";
        }

        $validatedData = $request->validate([
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $filename = md5($file->getBasename() . time()) . "." . $file->extension();
        $ogImage = \Image::make($file->getRealPath());
        $ogImage = $ogImage->save(public_path('images') . '/' . $filename);
        $ogImage->resize(150, 150);
        $ogImage->save(public_path('images') . '/' . "th_" . $filename);
        return $filename;
    }
}
