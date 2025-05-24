<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    protected $fillable = ['file_path', 'original_name', 'text_content'];

    protected $casts = [
        'tags' => 'array',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
