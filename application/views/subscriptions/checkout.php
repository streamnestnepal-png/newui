<style>
    .subscription-checkout-page { min-height: 70vh; padding: 54px 15px; }
    .subscription-checkout-panel { max-width: 860px; margin: 0 auto; overflow: hidden; background: #fff; box-shadow: 0 18px 50px rgba(15, 23, 42, .12); animation: checkoutReveal .5s ease both; }
    .subscription-checkout-intro { padding: 38px; background: linear-gradient(135deg, #172554, #0f766e); color: #fff; }
    .subscription-checkout-intro h1 { margin: 0 0 12px; color: #fff; font-size: 34px; }
    .subscription-checkout-intro p { margin: 0; color: #dbeafe; }
    .subscription-checkout-form { padding: 38px; }
    .subscription-checkout-form h2 { margin-bottom: 8px; color: #111827; font-size: 24px; }
    .subscription-checkout-summary { margin-bottom: 25px; color: #0f766e; font-weight: 700; }
    .subscription-phone-row { display: flex; gap: 8px; }
    .subscription-phone-row select { flex: 0 0 44%; }
    .subscription-phone-row input { flex: 1; }
    @keyframes checkoutReveal { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }
    @media (max-width: 575px) { .subscription-checkout-intro, .subscription-checkout-form { padding: 28px 20px; } .subscription-checkout-intro h1 { font-size: 28px; } .subscription-phone-row { display: block; } .subscription-phone-row select { width: 100%; margin-bottom: 8px; } }
</style>

<main class="container subscription-checkout-page">
    <section class="subscription-checkout-panel">
        <div class="subscription-checkout-intro">
            <p class="text-uppercase font-weight-bold mb-2" style="letter-spacing:2px;">Secure checkout</p>
            <h1>Complete Your Order</h1>
            <p>Enter your contact details to continue with this subscription.</p>
        </div>
        <form class="subscription-checkout-form" action="<?= base_url('payment/create-checkout') ?>" method="post">
            <h2>Customer Details</h2>
            <p class="subscription-checkout-summary"><?= html_escape($product) ?> &middot; <?= html_escape($package) ?> &middot; <?= html_escape($price) ?></p>
            <input type="hidden" name="product" value="<?= html_escape($product) ?>">
            <input type="hidden" name="package" value="<?= html_escape($package) ?>">
            <input type="hidden" name="package_price" value="<?= html_escape($price) ?>">
            <div class="form-group">
                <label for="subscription-customer-name">Full Name</label>
                <input class="form-control" id="subscription-customer-name" name="customer_name" type="text" required autocomplete="name" placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label for="subscription-email">Email</label>
                <input class="form-control" id="subscription-email" name="delivery_email" type="email" required autocomplete="email" placeholder="Enter your email address">
            </div>
            <div class="form-group">
                <label for="subscription-phone-country">Phone Number</label>
                <div class="subscription-phone-row">
                    <select class="form-control" id="subscription-phone-country" name="phone_country" required>
                        <option value="+977">🇳🇵 Nepal (+977)</option>
                        <option value="+91">🇮🇳 India (+91)</option>
                        <option value="+1">🇺🇸 United States (+1)</option>
                        <option value="+44">🇬🇧 United Kingdom (+44)</option>
                        <option value="+61">🇦🇺 Australia (+61)</option>
                        <option value="+971">🇦🇪 United Arab Emirates (+971)</option>
                        <option value="+974">🇶🇦 Qatar (+974)</option>
                        <option value="+81">🇯🇵 Japan (+81)</option>
                    </select>
                    <input class="form-control" id="subscription-phone-number" name="phone_number" type="tel" required inputmode="numeric" autocomplete="tel" pattern="[0-9]{10}" maxlength="10" placeholder="10 digit phone number">
                </div>
            </div>
            <button class="btn btn-danger btn-lg btn-block" type="submit">Continue to Payment &rarr;</button>
        </form>
    </section>
</main>

<script>
    (function () {
        var country = document.getElementById('subscription-phone-country');
        var phone = document.getElementById('subscription-phone-number');
        function updatePhoneRules() {
            var nepal = country.value === '+977';
            phone.pattern = nepal ? '[0-9]{10}' : '[0-9]{7,15}';
            phone.maxLength = nepal ? 10 : 15;
            phone.placeholder = nepal ? '10 digit phone number' : 'Phone number';
        }
        phone.addEventListener('input', function () { phone.value = phone.value.replace(/[^0-9]/g, ''); });
        country.addEventListener('change', updatePhoneRules);
        updatePhoneRules();
    }());
</script>