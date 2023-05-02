<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'amount',
        'total',
        'tax',
        'cash_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function boot()
    {
        parent::boot();

        function getTotal($sales): int
        {
            $total = $sales->amount * $sales->price;
            $total -= ($sales->tax / 100) * $total;

            return $total;
        }

        static::creating(function ($sales) {
            $sales->purchase_price = $sales->product->purchase_price;
            $sales->price = $sales->product->price;
        });

        static::created(function ($sales) {
            Product::where('id', $sales->product_id)->increment('amount', -1 * $sales->amount);

            $cash = Cash::create([
                'description' => "Penjualan produk : {$sales->amount} {$sales->product->name}",
                'nominal' => getTotal($sales),
                'type' => 'IN',
                'date' => today()
            ]);

            $sales->cash_id = $cash->id;
            $sales->save();
        });

        static::updated(function ($sales) {
            Cash::where('id', $sales->cash_id)->update(['nominal' => getTotal($sales)]);
        });

        static::deleted(function ($sales) {
            Product::where('id', $sales->product_id)->increment('amount', $sales->amount);
            Cash::find($sales->cash_id)->delete();
        });
    }

    public function calculated(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->amount * $this->price
        );
    }
}
