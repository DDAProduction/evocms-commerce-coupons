<?php
use Illuminate\Support\Facades\Route;

Route::any('/commerce/coupons/add',[\EvolutionCMS\EvocmsCommerceCoupons\Controllers\FrontController::class,'add']);