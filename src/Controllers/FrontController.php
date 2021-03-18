<?php


namespace EvolutionCMS\EvocmsCommerceCoupons\Controllers;


use EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCoupon;
use EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCouponOrder;
use EvolutionCMS\EvocmsCommerceCoupons\Rules\RulesLoader;
use Illuminate\Http\Request;

class FrontController
{
    /**
     * @var RulesLoader
     */
    private RulesLoader $rulesLoader;

    public function __construct(RulesLoader $rulesLoader)
    {
        $this->rulesLoader = $rulesLoader;
    }

    public function add(Request $request)
    {
        $coupon = $request->get('coupon');

        /** @var CommerceCoupon $coupon */
        $coupon = CommerceCoupon::select(['commerce_coupons.*'])
            ->selectRaw('count('.\DB::getTablePrefix().'commerce_coupon_orders.order_id) as used')
            ->where('coupon', $coupon)
            ->leftJoin('commerce_coupon_orders', 'commerce_coupons.id', '=', 'commerce_coupon_orders.coupon_id')
            ->where('active',1)
            ->groupBy('commerce_coupons.id')
            ->first()
        ;

        if(empty($coupon)){
            return [
                'status'=>false,
                'error'=>'empty',
                'message'=>\Lang::get('CommerceCoupons::main.error_empty')
            ];
        }


        foreach ($this->rulesLoader->loadRules() as $rule) {

            if($rule->getController()->check($coupon) !== true){
                return [
                    'status'=>false,
                    'error'=>'not-value',
                    'message'=>\Lang::get('CommerceCoupons::main.error_no_valid')
                ];
            }
        }

        $limit = intval($coupon->limit);
        $used = intval($coupon->used);



        if($limit > 0 && $limit<=$used){
            return [
                'status'=>false,
                'error'=>'limit',
                'message'=>\Lang::get('CommerceCoupons::main.error_limit')
            ];
        }

        $couponOrder = CommerceCouponOrder::create([
            'coupon_id'=>$coupon->id,
        ]);


        $_SESSION['CommerceCoupon'] = [
            'couponOrderId'=>$couponOrder->id,
            'coupon'=>$coupon->coupon,
            'discount_type'=>$coupon->discount_type,
            'discount_value'=>$coupon->discount_value,
        ];



        return [
            'status'=> true
        ];

    }
}