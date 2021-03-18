<?php


namespace EvolutionCMS\EvocmsCommerceCoupons\Rules\UsersRule;


use EvolutionCMS\EvocmsCommerceCoupons\Contracts\IRuleController;
use EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCoupon;

class UsersRuleController implements IRuleController
{

    public function check(CommerceCoupon $coupon)
    {
       $userId = evo()->getLoginUserID();

        if($userId === false){
            $userId = 0;
        }

        $users = $coupon->users->toArray();

        return empty($users) || in_array($userId,array_column($users,'internalKey'));

    }
}