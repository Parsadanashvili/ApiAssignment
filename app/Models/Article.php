<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    public function commentsThrough(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Comment::class,
            ArticleComment::class,
            'article_id',
            'id',
            'id',
            'comment_id'
        );
    }

    public function tagsThrough(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Tag::class,
            ArticleTag::class,
            'article_id',
            'id',
            'id',
            'tag_id'
        );
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            ArticleTag::class
        );
    }
}
