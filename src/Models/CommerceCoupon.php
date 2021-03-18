<?php


namespace EvolutionCMS\EvocmsCommerceCoupons\Models;


use EvolutionCMS\Models\UserAttribute;
use Illuminate\Database\Eloquent\Model;


/**
 * EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCoupon
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $coupon
 * @property float|null $discount_value
 * @property string|null $discount_type
 * @property int|null $limit
 * @property bool|null $active
 * @property \Illuminate\Support\Carbon|null $rule_period_from
 * @property \Illuminate\Support\Carbon|null $rule_period_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|UserAttribute[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon whereCoupon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon whereLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon whereRulePeriodFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon whereRulePeriodTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommerceCoupon whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CommerceCoupon extends Model
{

    protected $dates = [
        'rule_period_from','rule_period_to'
    ];

    protected $fillable = [
        'title','coupon','discount_value','discount_type','limit','active','rule_period_from','rule_period_to'
    ];

    public function users(){
        return $this->hasManyThrough(UserAttribute::class,CommerceCouponUser::class,'coupon_id','internalKey','id','user_id');
    }

}