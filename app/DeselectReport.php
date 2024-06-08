<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeselectReport extends Model
{
    protected $table = 'deselected_reports';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function createData(array $data)
    {
        $data['total_amount'] = $data['unit_price'] * $data['quantity'];
        $data['user_id'] = auth()->user()->id;

        return self::create($data);
    }

    public function updateData(array $data, self $obj)
    {
        $obj->quantity += $data['quantity'];
        $obj->unit_price = $data['unit_price'];
        $obj->total_amount += $data['quantity'] * $data['unit_price'];

        return $obj->save();
    }

    public function findByFilters(array $filters, $first = false, $paginate = false, $limit = 10, $datatable = false, $locations = null)
    {
        $query = self::query();

        if (! empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (! empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (! empty($filters['business_location_id'])) {
            $query->where('business_location_id', $filters['business_location_id']);
        } else {
            if (! empty($locations)) {
                $locations = array_filter(array_keys($locations->toArray()));
                $query->whereIn('business_location_id', $locations);
            }
        }

        if (! empty($filters['quantity'])) {
            $query->where('quantity', $filters['quantity']);
        }

        if (! empty($filters['unit_price'])) {
            $query->where('unit_price', $filters['unit_price']);
        }

        if (! empty($filters['total_amount'])) {
            $query->where('total_amount', $filters['total_amount']);
        }

        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $query->whereBetween(\DB::raw("DATE(updated_at)"), [$filters['start_date'], $filters['end_date']]);
        }

        $query = $query->orderBy('id', 'DESC');

        if ($first) {
            $query = $query->first();
        } else if ($paginate) {
            $query = $query->paginate($limit)->withQueryString();
        } else if ($datatable) {
            $query = $query->with('product', 'user', 'location');
        } else {
            $query = $query->get();
        }

        return $query;
        
    }
}
