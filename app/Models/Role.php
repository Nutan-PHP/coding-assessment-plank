<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    public const ADMIN = 'admin';
    public const EDITOR = 'editor';
    public const CONTRIBUTOR = 'contributor';

    protected $table = 'roles';
    protected $fillable = ['title', 'description', 'type'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }


}
