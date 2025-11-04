<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class FileUpload extends Model
{
     protected $fillable = ['user_id', 'filename', 'path', 'size', 'extension'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
