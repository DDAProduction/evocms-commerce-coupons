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



## Ограничения
 * требуется версия MySQL >=5.6
 * требуется версия php >=7.4

