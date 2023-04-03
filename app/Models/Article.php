<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'id', 'title', 'user_id'];

    public function authors()
    {
        return $this->belongsTo(User::class, "user_id");
    }

}
