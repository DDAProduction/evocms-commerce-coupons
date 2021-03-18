<?php

use EvolutionCMS\EvocmsDiscounts\Models\Discount;

Event::listen('evolution.OnWebPageInit',function (){
    evo()->regClientScript('assets/modules/evocms-commerce-coupons/public/front.js');
});

Event::listen(['evolution.OnCollectSubtotals'],function ($params){

    if(isset($_SESSION['CommerceCoupon'])){

        $coupon = $_SESSION['CommerceCoupon'];
        $discountValue = floatval($coupon['discount_value']);


        if($coupon['discount_type'] == 'amount'){
            $discount = $discountValue;
        }
        elseif($coupon['discount_type'] == 'percent'){
            $discount =  round($params['total'] * $discountValue / 100);
        }


        $params['total'] -= $discount;
        $params['rows']['discount'] = [
            'title' => \Lang::get('CommerceCoupons::main.subtotal_title',['coupon'=>$coupon['coupon']]),
            'price' => $discount,
        ];

    }
});

Event::listen(['evolution.OnOrderProcessed'],function ($params){
    if(isset($_SESSION['CommerceCoupon'])){


        $couponOrder = \EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCouponOrder::find($_SESSION['CommerceCoupon']['couponOrderId']);
        $couponOrder->order_id = $params['order']['id'];
        $couponOrder->save();

        unset($_SESSION['CommerceCoupon']);
    }

});