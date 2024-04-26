<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockBreaking extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_product_id',
        'child_product_id',
        'business_location_id',
        'breaking_quantity',
        'child_quantity',
        'created_by',
    ];

    public function assignedProduct()
    {
        return $this->hasOne(Product::class, 'id', 'child_product_id');
    }

    public function parentProduct()
    {
        return $this->hasOne(Product::class, 'id', 'parent_product_id');
    }

    public function businessLocation()
    {
        return $this->hasOne(BusinessLocation::class, 'id', 'business_location_id');
    }
}
