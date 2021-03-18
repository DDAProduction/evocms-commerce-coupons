<?php


namespace EvolutionCMS\EvocmsCommerceCoupons\Rules\UsersRule;


use EvolutionCMS\EvocmsCommerceCoupons\Assets;
use EvolutionCMS\EvocmsCommerceCoupons\Config;
use EvolutionCMS\EvocmsCommerceCoupons\Contracts\IRuleModuleController;
use EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCoupon;
use EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCouponUser;
use EvolutionCMS\EvocmsCommerceCoupons\Router\Router;
use EvolutionCMS\Models\UserAttribute;
use Illuminate\Http\Request;

class UsersRuleModuleController implements IRuleModuleController
{

    /**
     * @var Router
     */
    private Router $router;
    /**
     * @var Config
     */
    private Config $config;
    /**
     * @var Assets
     */
    private Assets $assets;

    public function __construct(Assets $assets, Router $router, Config $config)
    {

        $this->router = $router;
        $this->config = $config;
        $this->assets = $assets;
    }

    public function init()
    {
        $this->assets->addJs('assets/modules/evocms-commerce-coupons/public/rules/users.js');
        $this->router->addRoute('rule-users-search',[self::class,'search']);
    }

    public function search(Request $request){

        $q = UserAttribute::select(['internalKey as id','fullname as title']);

        if($request->has('checked')){
            $q->whereNotIn('id',explode(',',$request->get('checked')));
        }
        if($request->has('search')){
            $q->where('fullname','like','%'.$request->get('search').'%');
        }

        return $q->get()->toArray();
    }

    public function getRule(CommerceCoupon $coupon){
        if($coupon->users->toArray()){
            return $coupon->users;
        }
        return null;
    }


    public function saveRule($updateRow,$requestRules)
    {

        CommerceCouponUser::whereCouponId($updateRow['id'])->delete();

        if (isset($requestRules['users'])) {
            foreach ($requestRules['users'] as $user) {
                CommerceCouponUser::create([
                    'coupon_id' => $updateRow['id'],
                    'user_id' => $user['id'],
                ]);
            }
        }

        return $updateRow;
    }
}