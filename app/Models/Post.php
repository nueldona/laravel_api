<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = [
        'title', 'content', 'user_id', 'published_at', 'last_modified_at', 'status',
    ];
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }
}
