@if(!isset($_SESSION['CommerceCoupon']))
    <div class="mt-2 mb-2" data-commerce-coupon-owner>
        <div data-commerce-coupon-errors style="display: none"></div>
        <input type="text" placeholder="Купон" data-commerce-coupon>
        <button type="button" class="иет btn-secondary" data-commerce-coupon-add>Применить купон</button>

    </div>
@endif