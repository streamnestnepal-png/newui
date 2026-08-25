<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= html_escape($product) ?> | StreamNest</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>css/styling.css">
</head>

<body>
    <?php if (in_array($product, ['Mobile Legends', 'Free Fire', 'PUBG'], true)) : ?>
        <main class="container mlbb-topup-page">
            <div class="mlbb-topup-header">
                <p class="text-uppercase text-danger font-weight-bold mb-2"><?= html_escape($product) ?> Top-Up</p>
                <h1>Get your <?= $product === 'PUBG' ? 'UC' : 'Diamonds' ?> instantly.</h1>
                <p class="lead">Fast, secure, and easy to use.</p>
                <?php if ($product === 'PUBG') : ?><span class="stock-badge">In Stock</span><?php endif; ?>
            </div>

            <form class="mlbb-topup-form" action="<?= base_url('payment/create-checkout') ?>" method="post">
                <input type="hidden" name="product" value="<?= html_escape($product) ?>">
                <input type="hidden" name="package_price" id="package-price" value="<?= html_escape($packages[0][2] ?? '') ?>">
                <section class="topup-step">
                    <h2><span>1</span> Enter Your Account Data</h2>
                    <div class="form-row">
                        <div class="form-group col-md-6 <?= in_array($product, ['Free Fire', 'PUBG'], true) ? 'col-md-12' : '' ?>">
                            <label for="user_id">User ID</label>
                            <input class="form-control" id="user_id" name="user_id" type="text" required placeholder="Enter your User ID">
                        </div>
                        <?php if ($product === 'Mobile Legends') : ?>
                        <div class="form-group col-md-6">
                            <label for="server_id">Server ID</label>
                            <input class="form-control" id="server_id" name="server_id" type="text" required placeholder="Enter your Server ID">
                        </div>
                        <?php endif; ?>
                    </div>
                </section>

                <section class="topup-step">
                    <h2><span>2</span> Select the Amount You Want to Buy</h2>
                    <div class="diamond-grid">
                        <?php foreach ($packages as $index => $package) : ?>
                            <label class="diamond-option" for="package-<?= $index ?>">
                                <input id="package-<?= $index ?>" name="package" type="radio" value="<?= html_escape($package[0]) ?>" data-price="<?= html_escape($package[2]) ?>" <?= $index === 0 ? 'checked' : '' ?>>
                                <strong><?= html_escape($package[0]) ?></strong>
                                <?php if ($package[1] !== '') : ?><small>(<?= html_escape($package[1]) ?>)</small><?php endif; ?>
                                <b><?= html_escape($package[2]) ?></b>
                                <button class="btn btn-sm btn-outline-danger package-cart" type="button" data-product="<?= html_escape($product) ?>" data-package="<?= html_escape($package[0]) ?>" data-price="<?= html_escape($package[2]) ?>">Add to Cart</button>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="topup-step delivery-email-step">
                    <h2><span>3</span> Delivery Email</h2>
                    <label for="delivery_email">Where should we send your order details?</label>
                    <input class="form-control" id="delivery_email" name="delivery_email" type="email" required placeholder="Enter your email address">
                </section>

                <section class="topup-step coupon-step">
                    <h2><span>4</span> Have a Coupon Code?</h2>
                    <div class="input-group">
                        <input class="form-control" type="text" name="coupon" placeholder="Enter coupon code">
                        <div class="input-group-append"><button class="btn btn-outline-danger" type="button">Apply</button></div>
                    </div>
                </section>

                <div class="topup-actions">
                    <button class="btn btn-outline-danger btn-lg" type="button" id="add-to-cart-button" data-product="<?= html_escape($product) ?>">Add to Cart</button>
                    <button class="btn btn-danger btn-lg" type="submit">Buy Now!</button>
                </div>
                <p class="cart-status" id="cart-status" aria-live="polite"></p>
            </form>
        </main>
    <?php else : ?>
        <main class="container topup-page">
            <div class="topup-page__content">
                <p class="text-uppercase text-danger font-weight-bold mb-2">Digital Products &amp; Subscriptions</p>
                <h1><?= html_escape($product) ?> Top-Up</h1>
                <p class="lead">Choose your preferred package and enjoy your favorite digital service instantly.</p>
                <a href="<?= base_url('welcome') ?>" class="btn btn-danger">Back to Home</a>
            </div>
        </main>
    <?php endif; ?>
</body>

<script>
    document.querySelectorAll('.package-cart').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            var option = button.closest('.diamond-option');
            option.querySelector('input').checked = true;
            saveToCart(button.dataset.product, button.dataset.package, button.dataset.price);
        });
    });

    document.querySelectorAll('input[name="package"]').forEach(function (input) {
        input.addEventListener('change', function () {
            document.getElementById('package-price').value = input.dataset.price;
        });
    });

    var addToCartButton = document.getElementById('add-to-cart-button');
    if (addToCartButton) {
        addToCartButton.addEventListener('click', function () {
            var selected = document.querySelector('input[name="package"]:checked');
            if (selected) {
                var option = selected.closest('.diamond-option');
                saveToCart(addToCartButton.dataset.product, option.querySelector('strong').textContent, option.querySelector('b').textContent);
            }
        });
    }

    function saveToCart(product, packageName, price) {
        var items = JSON.parse(localStorage.getItem('gameinaCart') || '[]');
        items.push({ product: product, package: packageName, price: price });
        localStorage.setItem('gameinaCart', JSON.stringify(items));
        window.location.href = '<?= base_url('cart') ?>';
    }
</script>

</html>