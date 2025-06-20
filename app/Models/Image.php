<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */    protected $fillable = [
        'url',
        'path',
        'metadata',
        'adsense_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array'
    ];    /**
     * Get the adsense that owns the image.
     */
    public function adsense()
    {
        return $this->belongsTo(Adsense::class);
    }}
