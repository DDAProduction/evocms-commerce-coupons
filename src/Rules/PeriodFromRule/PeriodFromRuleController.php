<?php


namespace EvolutionCMS\EvocmsCommerceCoupons\Rules\PeriodFromRule;


use Carbon\Carbon;
use EvolutionCMS\EvocmsCommerceCoupons\Contracts\IRuleController;
use EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCoupon;

class PeriodFromRuleController implements IRuleController
{

    public function check(CommerceCoupon $coupon)
    {

        return empty($coupon->rule_period_from) || $coupon->rule_period_from->lte(Carbon::now());

    }
}