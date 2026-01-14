<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['total_item'];
    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }
    public function transactions()
    {
        return $this->hasMany(OrderTransaction::class);
    }
    public function cottage(){
        return $this->belongsTo(Cottage::class, 'customer_id');
    }
    public function getTotalItemAttribute()
    {
        return $this->products()->sum('quantity');
    }

    /**
     * Get the staff who took the order
     */
    public function orderTaker()
    {
        return $this->belongsTo(User::class, 'order_taker_id');
    }
}
