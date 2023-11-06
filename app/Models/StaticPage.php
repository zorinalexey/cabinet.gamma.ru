<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class StaticPage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function short_content(): string
    {
        $content = preg_replace('~<(.+)>~', '', $this->content);

        return mb_substr($content, 0, 350).'...';
    }
}
