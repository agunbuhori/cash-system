<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'nominal',
        'date',
        'number',
        'detail'
    ];

    protected $casts = [
        'date' => 'date',
        'detail' => 'json'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($cash) {
            $counts = Cash::where('type', $cash->type)->count();

            if ($counts) {
                $last = Cash::orderBy('id', 'desc')->where('type', $cash->type)->limit(1)->first();
                $counts = (int) explode('/', $last->number)[0];
            }

            $no = str_pad((string)$counts + 1, 5, "0", STR_PAD_LEFT);
            $number = "{$no}/{$cash->type}/{$cash->date->format('m')}/{$cash->date->format('Y')}";

            $cash->number = $number;

            if ($cash->type === 'OUT') {
                $cash->nominal = -1 * $cash->nominal;
            } else {
                $cash->nominal = abs($cash->nominal);
            }
        });
    }
}
