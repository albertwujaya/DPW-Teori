<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // The old DB has no timestamps for pengaturan

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Helper method to get a setting value safely
     */
    public static function getValue($key, $default = '')
    {
        $setting = self::find($key);
        return $setting ? $setting->value : $default;
    }
}
