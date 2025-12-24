<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
     // Allow mass assignment for 'name'
    protected $fillable = ['name'];

    // A role belongs to many users
     
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
}
