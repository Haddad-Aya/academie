<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademyGalleryItem extends Model
{
    protected $fillable = ['image_path', 'caption', 'active', 'sort_order'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
