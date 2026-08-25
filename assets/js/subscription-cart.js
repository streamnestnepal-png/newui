(function () {
    var activeProduct = null;
    document.querySelectorAll('.subscription-product').forEach(function (product) {
        product.addEventListener('click', function (event) {
            if (event.target.closest('.subscription-buy, .subscription-add')) return;

            document.querySelectorAll('.subscription-product').forEach(function (item) {
                if (item !== product) {
                    item.querySelectorAll('.subscription-buy, .subscription-add').forEach(function (button) {
                        button.hidden = true;
                        button.classList.remove('subscription-add-visible');
                    });
                }
            });

            product.querySelectorAll('.subscription-buy, .subscription-add').forEach(function (button) {
                button.hidden = false;
                button.classList.add('subscription-add-visible');
            });
            activeProduct = product;
        });
    });

    document.querySelectorAll('.subscription-add').forEach(function (button) {
        button.addEventListener('click', function () {
            var items = JSON.parse(localStorage.getItem('gameinaCart') || '[]');
            items.push({ product: button.dataset.product, package: button.dataset.package, price: button.dataset.price });
            localStorage.setItem('gameinaCart', JSON.stringify(items));
            button.textContent = 'Added to Cart';
            button.disabled = true;
        });
    });

    document.querySelectorAll('.subscription-buy').forEach(function (button) {
        button.addEventListener('click', function () {
            var addButton = button.parentElement.querySelector('.subscription-add');
            if (addButton) {
                var checkoutPage = addButton.dataset.checkoutPage;
                var query = '?product=' + encodeURIComponent(addButton.dataset.product) +
                    '&package=' + encodeURIComponent(addButton.dataset.package) +
                    '&price=' + encodeURIComponent(addButton.dataset.price);
                window.location.href = checkoutPage + query;
            }
        });
    });
}());