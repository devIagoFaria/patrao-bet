<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryButton extends Model
{
    use HasFactory;

    protected $table = 'category_buttons';

    protected $fillable = [
        'group',
        'title',
        'slug',
        'image_path',
        'icon_class',
        'subtitle',
        'distribution',
        'badge_value',
        'action_type',
        'action_value',
        'is_active',
        'order',
    ];
}
