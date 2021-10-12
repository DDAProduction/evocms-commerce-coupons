# EvocmsCommerceCoupons
Пакет для создания и использования купоны для модуля Commerce, во много вдохновлен дополнением webber12/CommerceCoupons

## Установка
1. php artisan package:installrequire ddaproduction/evocms-commerce-coupons "*"
2. php artisan vendor:publish --provider="EvolutionCMS\EvocmsCommerceCoupons\EvocmsCommerceCouponsServiceProvider"
3. php artisan migrate
4. В форму заказа добавить блок для добавления купона ```@include('CommerceCoupons::form')```

## Возможности
* Автогенерация купонов
* Ручное создание купонов
* Ограничение периода действия и пользователей которые могут использовать купон
* Активные и неактивные купоны
* Лимит использований (если поставить 0, будет безлимитный)

## Работа с переводами
Создаем файл в соотвествующей языковой папке и можем переопределить необходимые нам значения (core/lang/vendor/CommerceCoupons/ru/main.php).

## Расчитываем скидку
    В плагин OnCollectSubtotals добавляем следующий код:
```
Event::listen('evolution.OnCollectSubtotals', function ($params) use ($deliveries) {
   ...
    if(isset($_SESSION['CommerceCoupon'])){

        $coupon = $_SESSION['CommerceCoupon'];
        $discountValue = floatval($coupon['discount_value']);

        // если скидка задана числом
        if($coupon['discount_type'] == 'amount'){
            $discount = $discountValue;
        }
        
        //если скидка задана в процентах        
        elseif($coupon['discount_type'] == 'percent'){
            $discount =  round(($params['total']) * $discountValue / 100);
        }

        // пересчитываем результат с учетом скидки
        $params['total'] -= $discount;
        
        // фомируем массив с данными необходимыми нам для отображения
        $params['rows']['coupons'] = [
            'main_title' => \Lang::get('CommerceCoupons::main.subtotal_title',['coupon'=>$coupon['coupon']]),
            'price' => -$discount
        ];

    }
    ...
})
```
        В зависимости от того что должна включать в себя скидка, вы вставляеете этот код или до, или после того как вы посчитаете свои subtotals (доставку например). То есть вы можете посчитать все что вам необходимо без учета скидки разместив этот код в начале плагина, или же наоборот, посчитать скидку в самом конце и тогда она будет включать в себя все что вы добавити в subtotals (включая например доставку).

## Ограничения
 * требуется версия MySQL >=5.6
 * требуется версия php >=7.4

