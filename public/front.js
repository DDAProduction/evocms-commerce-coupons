document.addEventListener('click', function (e) {
    if (e.target.closest('[data-commerce-coupon-add]')) {
        e.preventDefault();
        let errors = document.querySelector('[data-commerce-coupon-errors]');
        let coupon = document.querySelector('[data-commerce-coupon]').value;
        const data = new URLSearchParams();
        data.append('coupon', coupon);
        fetch('/commerce/coupons/add', {method: 'post', body: data})
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                if (data.status) {
                    document.querySelector('[data-commerce-coupon-owner]').style.display = 'none';
                    Commerce.updateOrderData(document.querySelector('[data-commerce-order]'));
                    errors.style.display = 'none';
                } else {
                    errors.innerHTML = data.message;
                    errors.style.display = 'block';
                }
            });
    }
})