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

    public function importExternalImageUrl()
    {
        $upload = Cloudinary::upload($this->external_image_url, ['folder' => 'mtg']);

        $this->update(['internal_image_url' => $upload->getSecurePath()]);
    }
}
