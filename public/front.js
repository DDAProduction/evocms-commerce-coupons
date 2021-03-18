$(document).on('click','[data-commerce-coupon-add]',function () {

    let $errors = $('[data-commerce-coupon-errors]');

    let coupon = $('[data-commerce-coupon]').val();

    $.post('/commerce/coupons/add',{
        coupon
    },function (response) {


        if(response.status){
            $('[data-commerce-coupon-owner]').hide();
            Commerce.updateOrderData($('[data-commerce-order]'));
            $errors.hide();
        }
        else{
            $errors.html(response.message);
            $errors.show();
        }


    })

});