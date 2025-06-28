<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Infographic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'caption',
        'image',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($infographic) {
            if ($infographic->image && Storage::disk('public')->exists($infographic->image)) {
                Storage::disk('public')->delete($infographic->image);
            }
        });
    }
}
