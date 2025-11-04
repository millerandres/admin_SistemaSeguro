<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Group extends Model
{
    protected $fillable = ['name', 'quota_limit'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_groups');
    }
}
