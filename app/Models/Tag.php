<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

   public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function tenders()
    {
        return $this->belongsToMany(Tender::class);
    }
}

