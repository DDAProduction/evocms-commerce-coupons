<?php


namespace EvolutionCMS\EvocmsCommerceCoupons\Controllers;


use EvolutionCMS\EvocmsCommerceCoupons\Assets;
use EvolutionCMS\EvocmsCommerceCoupons\Models\CommerceCoupon;
use EvolutionCMS\EvocmsCommerceCoupons\Rules\RulesLoader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ModuleController
{
    private $moduleUrl;
    /**
     * @var Assets
     */
    private Assets $assets;
    /**
     * @var RulesLoader
     */
    private RulesLoader $rulesLoader;

    public function __construct($moduleUrl, Assets $assets, RulesLoader $rulesLoader)
    {
        $this->moduleUrl = $moduleUrl;
        $this->assets = $assets;
        $this->rulesLoader = $rulesLoader;
    }

    public function show()
    {

        return View::make('CommerceCoupons::module')->with([
            'css' => $this->assets->getCss(),
            'js' => $this->assets->getJs(),
            'moduleUrl' => $this->moduleUrl,
            'lang' => $this->loadLang(),
        ])->render();
    }

    public function load(Request $request)
    {
        $start = $request->has('start') ? $request->get('start') : 0;
        $count = $request->has('count') ? $request->get('count') : 10;

        $query = CommerceCoupon::with([
            'users' => function ($query) {
                return $query->select(['user_attributes.id', 'fullname as title']);
            },
        ])
            ->select(['commerce_coupons.*'])
            ->selectRaw('count('.\DB::getTablePrefix().'commerce_coupon_orders.order_id) as used')
        ;

        if($request->has('sort')){
            $sort = $request->get('sort');
            $sortBy = key($sort);
            $sortOrder = $sort[$sortBy];

            $sortBy = $this->prepareFieldName($sortBy);

            $query->orderBy($sortBy, $sortOrder);
        }
        else{
            $query->orderBy('commerce_coupons.id', 'desc');
        }

        if($request->has('filter')){

            $filters = $request->get('filter');

            foreach ($filters as $field => $filterValue) {
                if(empty($filterValue)){
                    continue;
                }

                $field = $this->prepareFieldName($field);

                $query->where($field,'like','%'.$filterValue.'%');
            }

        }



        $total = $query->count();

        $query->groupBy('commerce_coupons.id');
        $query->leftJoin('commerce_coupon_orders','commerce_coupons.id','=','commerce_coupon_orders.coupon_id');

        $discounts = $query->limit($count)->offset($start)->get();


        $result = [
            "total_count" => $total,
            "pos" => $start,
            "data" => []
        ];



        /** @var CommerceCoupon[] $discounts */
        foreach ($discounts as $discount) {


            $item = [
                'id' => $discount->id,
                'title' => $discount->title,
                'coupon' => $discount->coupon,
                'created_at' => $discount->created_at->format('d-m-Y H:i:s'),

                'discount_value' => $discount->discount_value,
                'discount_type' => $discount->discount_type,

                'limit' => $discount->limit,
                'used' => $discount->used,

                'active' => $discount->active,
                'rules' => []
            ];

            foreach ($this->rulesLoader->loadRules() as $ruleId => $rule) {

                $preparedRule = $rule->getModuleController()->getRule($discount);
                if ($preparedRule) {
                    $item['rules'][$ruleId] = $preparedRule;
                }

            }

            $result['data'][] = $item;
        }

        return $result;
    }

    public function add(){
        $coupon  = CommerceCoupon::create();

        return [
            'status'=>true,
            'newid'=>$coupon->id
        ];
    }

    public function update(Request $request){
        $request = $request->toArray();
        $request['rules'] = json_decode($request['rules'],true);

        $coupon = CommerceCoupon::findOrFail($request['id']);

        $data = [
            'id' => $request['id'],
            'title' => $request['title'],
            'coupon' => $request['coupon'],
            'discount_value' => $request['discount_value'],
            'discount_type' => $request['discount_type'],
            'limit' => $request['limit'],
            'active' => $request['active'],
        ];

        foreach ($this->rulesLoader->loadRules() as $rule) {
            $data = $rule->getModuleController()->saveRule($data, $request['rules']);
        }

        unset($data['id']);
        $coupon->fill($data);
        $coupon->save();

        return [
            'status'=>true
        ];
    }

    public function remove(Request $request){
        CommerceCoupon::findOrFail($request->post('id'))->delete();
    }

    public function generate(Request $request)
    {
        $request = $request->toArray();

        for ($i = 0; $i < intval($request['coupons_count']); $i++) {

            $coupon = $this->generateCode($request['symbol_counts']);


            $data = [
                'title' => $request['title'],
                'coupon' => $coupon,
                'discount_value' => $request['discount_value'],
                'discount_type' => $request['discount_type'],
                'limit' => $request['limit'],
                'active' => $request['active'],
            ];

            $coupon = CommerceCoupon::create();
            $data['id'] = $coupon->id;

            foreach ($this->rulesLoader->loadRules() as $rule) {
                $data = $rule->getModuleController()->saveRule($data, $request['rules']);
            }


            unset($data['id']);
            $coupon->fill($data);

            $coupon->save();

        }

        return [
            'status'=>true
        ];
    }

    private function loadLang()
    {
        $lang = [];

        $path = __DIR__ . "/../../lang/" . \Lang::getLocale() . "/";

        $files = scandir($path);
        if (is_array($files)) {
            foreach (scandir($path) as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }

                $key = explode('.', $file)[0];
                $lang[$key] = require $path . "/" . $file;

            }
        }

        return $lang['main'];
    }

    private function generateCode($symbolCount)
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < $symbolCount; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $res;
    }

    private function prepareFieldName($sortBy)
    {
        if(in_array($sortBy,['id','created_at'])){
            $sortBy  = 'commerce_coupons.'.$sortBy;
        }
        return $sortBy;
    }
}