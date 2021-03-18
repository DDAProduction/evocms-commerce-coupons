<?php


namespace EvolutionCMS\EvocmsCommerceCoupons\Rules\PeriodToRule;


use Carbon\Carbon;
use EvolutionCMS\EvocmsCommerceCoupons\Contracts\IRuleController;
use EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCoupon;

class PeriodToRuleController implements IRuleController
{

    public function check(CommerceCoupon $coupon)
    {

        return empty($coupon->rule_period_to) || $coupon->rule_period_to->gte(Carbon::now());

    }
}