<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductLink extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_product_id',
        'child_product_id',
        'quantity'
    ];

    public function assignedProduct()
    {
        return $this->hasOne(Product::class, 'id', 'child_product_id');
    }

    public function parentProduct()
    {
        return $this->hasOne(Product::class, 'id', 'parent_product_id');
    }
}
