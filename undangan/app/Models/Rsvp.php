<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    use HasFactory;

    protected $table = 'rsvp';

    protected $fillable = [
        'nama',
        'slug',
        'kehadiran',
        'jumlah_tamu',
        'ucapan',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null; // The old DB only has created_at
}
