<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameCategory extends Model
{
    use HasFactory;

    protected $table = 'game_categories';

    protected $fillable = [
        'title',
        'slug',
        'icon_class',
        'layout_type',
        'show_ver_todos',
        'is_active',
        'order',
    ];
}
