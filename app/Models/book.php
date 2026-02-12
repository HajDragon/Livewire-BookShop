<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'author',
        'rating',
        'cover_url',
        'isbn',
        'openlibrary_key',
        'publisher',
        'description',
        'publish_year',
        'pages',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }
}
