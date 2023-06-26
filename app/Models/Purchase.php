<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'cash_id',
        'description'
    ];


    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $cash = Cash::create([
                'description' => "Pembelian : {$model->amount} {$model->title}",
                'nominal' => $model->price,
                'type' => 'OUT',
                'date' => today()
            ]);

            $model->cash_id = $cash->id;
            $model->save();
        });
    }
}
