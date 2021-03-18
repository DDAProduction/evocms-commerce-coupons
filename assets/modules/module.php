<?php

use EvolutionCMS\EvocmsCommerceCoupons\Controllers\ModuleController;
use EvolutionCMS\EvocmsCommerceCoupons\Router\Router;
use EvolutionCMS\EvocmsCommerceCoupons\Rules\RulesLoader;
use Illuminate\Http\Request;

$request = Request::createFromGlobals();

$di = [
    'moduleUrl' => 'index.php?a=112&id='.$request->query('id').'&'
];

$assets = evo()->make(\EvolutionCMS\EvocmsCommerceCoupons\Assets::class);

$assets->addCss("manager/media/style/common/font-awesome/css/font-awesome.min.css");
$assets->addCss("assets/modules/evocms-commerce-coupons/public/codebase/webix.min.css");
$assets->addCss("assets/modules/evocms-commerce-coupons/public/codebase/skins/skin.css");
$assets->addCss("core/custom/packages/evocms-commerce-coupons/public/style.css");

$assets->addJs("assets/js/jquery.min.js");
$assets->addJs("assets/modules/evocms-commerce-coupons/public/codebase/webix.min.js");
$assets->addJs("assets/modules/evocms-commerce-coupons/public/app.js");


$router = evo()->make(Router::class);

$router->addRoute('coupons-show', [ModuleController::class, 'show']);
$router->addRoute('coupons-load', [ModuleController::class, 'load']);
$router->addRoute('coupons-update', [ModuleController::class, 'update']);
$router->addRoute('coupons-remove', [ModuleController::class, 'remove']);
$router->addRoute('coupons-add', [ModuleController::class, 'add']);
$router->addRoute('coupons-generate', [ModuleController::class, 'generate']);

$ruleLoader = evo()->make(RulesLoader::class);
$ruleLoader->initRules($ruleLoader->loadRules());


$action = $request->has('action')?$request->get('action'):'coupons-show';

$route = $router->match($action);

$response = call_user_func_array([evo()->make($route[0],$di),$route[1]],[$request]);

if(is_array($response)){
    header('Content-type:text/json');
    echo json_encode($response,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
else{
    echo $response;
}