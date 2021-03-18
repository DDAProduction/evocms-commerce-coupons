<?php


namespace EvolutionCMS\EvocmsCommerceCoupons\Contracts;



use EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCoupon;

interface IRuleModuleController
{
    public function init();
    public function getRule(CommerceCoupon $coupon);
    public function saveRule($updateRow,$requestRules);
}