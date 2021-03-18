<?php


namespace EvolutionCMS\EvocmsCommerceCoupons\Rules\PeriodFromRule;


use EvolutionCMS\EvocmsCommerceCoupons\Assets;
use EvolutionCMS\EvocmsCommerceCoupons\Contracts\IRuleModuleController;
use EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCoupon;
use Illuminate\Support\Carbon;

class PeriodFromRuleModuleController implements IRuleModuleController
{

    public function __construct(Assets $assets)
    {
        $assets->addJs('assets/modules/evocms-commerce-coupons/public/rules/period_from.js');
    }

    public function init()
    {
        // TODO: Implement init() method.
    }


    public function getRule(CommerceCoupon $coupon){

        if($coupon->rule_period_from){
            return  $coupon->rule_period_from->format('Y-m-d H:i:s');
        }
        return null;
    }
    public function saveRule($updateRow,$requestRules)
    {


        if(!is_null($requestRules['periodFrom'])){
            $updateRow['rule_period_from'] = Carbon::parse($requestRules['periodFrom']);
        }
        else{
            $updateRow['rule_period_from'] = null;
        }

        return $updateRow;
    }
}