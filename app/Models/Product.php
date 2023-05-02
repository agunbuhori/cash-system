<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_category_id',
        'code',
        'name',
        'amount',
        'price',
        'purchase_price',
        'description',
        'picture',
        'update_cash'
    ];

    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $counts = Product::count();

            if ($counts) {
                $last = Product::orderBy('id', 'desc')->first();
                $counts = (int) $last->code;
            }

            $no = str_pad((string)$counts + 1, 5, "0", STR_PAD_LEFT);
            $product->code = $no;
        });

        static::created(function ($product) {
            if ($product->update_cash) {
                Cash::create([
                    'type' => 'OUT',
                    'nominal' => $product->purchase_price * $product->amount,
                    'description' => "Pembelian/produksi produk : {$product->amount} {$product->name}",
                    'date' => today()
                ]);
            }
        });
    }
}
