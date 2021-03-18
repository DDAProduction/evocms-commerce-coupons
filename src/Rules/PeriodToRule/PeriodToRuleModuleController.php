<?php


namespace EvolutionCMS\EvocmsCommerceCoupons\Rules\PeriodToRule;


use EvolutionCMS\EvocmsCommerceCoupons\Assets;
use EvolutionCMS\EvocmsCommerceCoupons\Contracts\IRuleModuleController;
use EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCoupon;
use Illuminate\Support\Carbon;

class PeriodToRuleModuleController implements IRuleModuleController
{

    /**
     * @var Assets
     */
    private Assets $assets;

    public function __construct(Assets $assets)
    {
        $this->assets = $assets;
    }

    public function init()
    {
        $this->assets->addJs('assets/modules/evocms-commerce-coupons/public/rules/period_to.js');

    }


    public function getRule(CommerceCoupon $coupon){
        if($coupon->rule_period_to){
            return  $coupon->rule_period_to->format('Y-m-d H:i:s');
        }
        return null;
    }

    public function saveRule($updateRow,$requestRules)
    {
        if(!is_null($requestRules['periodTo'])){
            $updateRow['rule_period_to'] = Carbon::parse($requestRules['periodTo']);
        }
        else{
            $updateRow['rule_period_to'] = null;
        }

        return $updateRow;
    }
}