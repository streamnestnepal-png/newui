<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cart | StreamNest</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>css/styling.css">
    <style>
        .phone-row { display: flex; gap: 8px; }
        .phone-country { flex: 0 0 42%; }
        .phone-number { flex: 1; }
        @media (max-width: 480px) { .phone-row { display: block; } .phone-country { width: 100%; margin-bottom: 8px; } }
    </style>
</head>

<body>
    <main class="container cart-page">
        <div class="cart-header">
            <p class="text-uppercase text-danger font-weight-bold mb-2">Digital Products &amp; Subscriptions</p>
            <h1>Your Cart</h1>
        </div>
        <div id="cart-items" class="cart-items"></div>
        <div class="cart-empty" id="cart-empty">Your cart is empty.</div>
        <div class="cart-footer">
            <a href="<?= base_url('welcome') ?>" class="btn btn-outline-danger">Continue Shopping</a>
            <button class="btn btn-danger" id="checkout-button" type="button">Proceed to Checkout</button>
        </div>
        <form class="cart-checkout-form" id="cart-checkout-form" action="<?= base_url('payment/create-checkout') ?>" method="post" hidden>
            <h2>Enter Your Account Data</h2>
            <p class="cart-selected-product" id="cart-selected-product"></p>
            <input type="hidden" name="product" id="cart-product">
            <input type="hidden" name="package" id="cart-package">
            <input type="hidden" name="package_price" id="cart-price">
            <div class="form-group">
                <label for="cart-customer-name">Full Name</label>
                <input class="form-control" id="cart-customer-name" name="customer_name" type="text" required placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label for="cart-user-id">User ID</label>
                <input class="form-control" id="cart-user-id" name="user_id" type="text" placeholder="Enter your User ID">
            </div>
            <div class="form-group" id="server-id-group">
                <label for="cart-server-id">Server ID</label>
                <input class="form-control" id="cart-server-id" name="server_id" type="text" placeholder="Enter your Server ID">
            </div>
            <div class="form-group">
                <label for="cart-delivery-email">Delivery Email</label>
                <input class="form-control" id="cart-delivery-email" name="delivery_email" type="email" required placeholder="Enter your email address">
            </div>
            <div class="form-group">
                <label for="cart-phone-country">Phone Number</label>
                <div class="phone-row">
                    <select class="form-control phone-country" id="cart-phone-country" name="phone_country" required>
                        <option value="+977" data-country="Nepal">🇳🇵 Nepal (+977)</option>
                        <option value="+91" data-country="India">🇮🇳 India (+91)</option>
                        <option value="+1" data-country="United States">🇺🇸 United States (+1)</option>
                        <option value="+44" data-country="United Kingdom">🇬🇧 United Kingdom (+44)</option>
                        <option value="+61" data-country="Australia">🇦🇺 Australia (+61)</option>
                        <option value="+971" data-country="United Arab Emirates">🇦🇪 United Arab Emirates (+971)</option>
                        <option value="+974" data-country="Qatar">🇶🇦 Qatar (+974)</option>
                        <option value="+81" data-country="Japan">🇯🇵 Japan (+81)</option>
                    </select>
                    <input class="form-control phone-number" id="cart-phone-number" name="phone_number" type="tel" required inputmode="numeric" pattern="[0-9]{10}" maxlength="10" placeholder="10 digit phone number">
                </div>
            </div>
            <button class="btn btn-danger btn-lg btn-block" type="submit">Continue to Payment &rarr;</button>
        </form>
    </main>

    <script>
        (function () {
            var items = JSON.parse(localStorage.getItem('gameinaCart') || '[]');
            var container = document.getElementById('cart-items');
            var empty = document.getElementById('cart-empty');
            var checkout = document.getElementById('checkout-button');
            var phoneCountry = document.getElementById('cart-phone-country');
            var phoneNumber = document.getElementById('cart-phone-number');

            <?php if ($this->session->userdata('email')) : ?>
            if (items.length) {
                fetch('<?=base_url('cart/sync_abandoned');?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(items)
                });
            }
            <?php endif; ?>

            function updatePhoneRules() {
                var nepal = phoneCountry.value === '+977';
                phoneNumber.pattern = nepal ? '[0-9]{10}' : '[0-9]{7,15}';
                phoneNumber.maxLength = nepal ? 10 : 15;
                phoneNumber.placeholder = nepal ? '10 digit phone number' : 'Phone number';
            }

            phoneNumber.addEventListener('input', function () { phoneNumber.value = phoneNumber.value.replace(/[^0-9]/g, ''); });
            phoneCountry.addEventListener('change', updatePhoneRules);
            updatePhoneRules();

            if (!items.length) {
                checkout.disabled = true;
                return;
            }

            checkout.addEventListener('click', function () {
                var item = items[0];
                document.getElementById('cart-checkout-form').hidden = false;
                document.getElementById('cart-product').value = item.product;
                document.getElementById('cart-package').value = item.package;
                document.getElementById('cart-price').value = item.price;
                document.getElementById('cart-selected-product').textContent = item.product + ' - ' + item.package + ' (' + item.price + ')';
                document.getElementById('cart-user-id').closest('.form-group').hidden = item.product !== 'Mobile Legends';
                document.getElementById('cart-user-id').required = item.product === 'Mobile Legends';
                document.getElementById('server-id-group').hidden = item.product !== 'Mobile Legends';
                document.getElementById('cart-server-id').required = item.product === 'Mobile Legends';
                document.getElementById('cart-checkout-form').scrollIntoView({ behavior: 'smooth' });
            });

            empty.hidden = true;
            items.forEach(function (item, index) {
                var row = document.createElement('div');
                row.className = 'cart-item';
                row.innerHTML = '<div><strong>' + item.product + '</strong><span>' + item.package + '</span></div>' +
                    '<b>' + item.price + '</b><button class="btn btn-sm btn-outline-danger" data-index="' + index + '">Remove</button>';
                container.appendChild(row);
            });

            container.addEventListener('click', function (event) {
                if (!event.target.dataset.index) return;
                items.splice(Number(event.target.dataset.index), 1);
                localStorage.setItem('gameinaCart', JSON.stringify(items));
                location.reload();
            });
        }());
    </script>
</body>

</html>