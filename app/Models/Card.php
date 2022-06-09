<?php

namespace App\Models;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $casts = [
        'meta' => 'json',
    ];

    public function getImageUrlAttribute()
    {
        return $this->internal_image_url ?? $this->external_image_url;
    }

    public function importImageUrl()
    {
        $upload = Cloudinary::upload($this->external_image_url, ['folder' => 'mtg']);

        $this->update(['internal_image_url' => $upload->getSecurePath()]);
    }
}
