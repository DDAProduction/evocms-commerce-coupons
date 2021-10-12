<?php

use EvolutionCMS\EvocmsDiscounts\Models\Discount;

Event::listen('evolution.OnWebPageInit',function (){
    evo()->regClientScript('assets/modules/evocms-commerce-coupons/public/front.js');
});

Event::listen(['evolution.OnOrderSaved'],function ($params){
    if(evo()->isFrontend() && isset($_SESSION['CommerceCoupon'])){

        $couponOrder = \EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCouponOrder::find($_SESSION['CommerceCoupon']['couponOrderId']);
        $couponOrder->order_id = $params['order_id'];
        $couponOrder->save();

        unset($_SESSION['CommerceCoupon']);
    }

});