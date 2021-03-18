<?php

namespace EvolutionCMS\EvocmsCommerceCoupons\Contracts;

use EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCoupon;

interface IRuleController
{

    public function check(CommerceCoupon $coupon);

}