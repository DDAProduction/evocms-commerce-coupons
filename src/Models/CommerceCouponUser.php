<?php


namespace EvolutionCMS\EvocmsCommerceCoupons\Models;


use Illuminate\Database\Eloquent\Model;



/**
 * EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCouponUser
 *
 * @property int $id
 * @property int $coupon_id
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponUser whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCouponUser whereUserId($value)
 * @mixin \Eloquent
 */
class CommerceCouponUser extends Model
{
    protected $fillable = [
        'user_id','coupon_id'
    ];
    public $timestamps = false;
}