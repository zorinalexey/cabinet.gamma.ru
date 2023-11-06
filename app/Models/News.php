<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class News extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string[]
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'alias',
        'title',
        'content',
    ];

    public function short_content(): string
    {
        $content = preg_replace('~<(.+)>~uU', '', $this->content);

        return mb_substr($content, 0, 350).'...';
    }

    public function documents(): HasMany
    {
        return $this->hasMany(NewsDocuments::class, 'post_id', 'id');
    }
}
