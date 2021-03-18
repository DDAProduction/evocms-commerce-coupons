<?php


namespace EvolutionCMS\EvocmsCommerceCoupons\Models;


use Illuminate\Database\Eloquent\Model;


/**
 * EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCouponOrder
 *
 * @property int $id
 * @property int $coupon_id
 * @property int $order_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponOrder whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CommerceCouponOrder extends Model
{

    protected $fillable = [
        'coupon_id','order_id'
    ];

}