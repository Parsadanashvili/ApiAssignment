<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $hidden = [
        'laravel_through_key'
    ];

    public function articles(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Article::class,
            ArticleTag::class,
            'tag_id',
            'id',
            'id',
            'article_id'
        );
    }

}
