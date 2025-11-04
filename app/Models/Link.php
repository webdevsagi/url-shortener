<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
{
    protected $fillable = [
        'slug',
        'target_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function hits(): HasMany
    {
        return $this->hasMany(LinkHit::class);
    }

    public static function generateUniqueSlug(int $length = 6): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $maxAttempts = 10;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $slug = '';
            for ($i = 0; $i < $length; $i++) {
                $slug .= $characters[random_int(0, strlen($characters) - 1)];
            }

            if (!self::where('slug', $slug)->exists()) {
                return $slug;
            }
        }

        // If we couldn't generate a unique slug, try with a longer length
        return self::generateUniqueSlug($length + 1);
    }
}
