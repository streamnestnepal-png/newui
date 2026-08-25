<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thank You | StreamNest</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>css/styling.css">
</head>
<body>
    <style>
        body { background: #f8fafc; }
        .payment-complete { min-height: 78vh; display: grid; place-items: center; padding: 40px 15px; }
        .payment-card { max-width: 620px; padding: 52px 32px; background: #fff; text-align: center; box-shadow: 0 18px 50px rgba(15, 23, 42, .12); animation: paymentReveal .55s ease both; }
        .payment-check { width: 70px; height: 70px; margin: 0 auto 22px; border-radius: 50%; background: #0f766e; color: #fff; font-size: 42px; line-height: 70px; animation: paymentPop .45s .15s ease both; }
        .payment-card h1 { margin-bottom: 14px; color: #111827; }
        .payment-card p { color: #64748b; }
        .payment-email { color: #0f766e !important; font-weight: 700; }
        @keyframes paymentReveal { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes paymentPop { from { transform: scale(.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>
    <main class="container payment-complete">
        <section class="payment-card">
            <div class="payment-check">&#10003;</div>
            <h1 id="payment-title">Your Order Was Successful!</h1>
            <p class="lead" id="payment-message">Thank you for your purchase.</p>
            <p>Please wait a moment while we process your order.</p>
            <p id="payment-status"><strong>Status:</strong> <?= !empty($order) && $order['status'] === 'paid' ? 'Payment Successful / Order Processing' : 'Payment Verification Pending' ?></p>
            <?php if (!empty($order)) : ?>
                <div class="order-summary text-left mt-4 mb-3">
                    <p><strong>Product:</strong> <?= html_escape($order['product']) ?></p>
                    <p><strong>Package:</strong> <?= html_escape($order['package']) ?></p>
                    <p><strong>Order ID:</strong> <?= html_escape($order['order_id']) ?></p>
                    <p><strong>Amount:</strong> NPR <?= number_format((int) $order['amount'] / 100, 2) ?></p>
                </div>
            <?php endif; ?>
            <p class="payment-email">Check your email to get your product details.</p>
            <a href="<?= base_url('welcome') ?>" class="btn btn-danger mt-3">Back to Home &rarr;</a>
        </section>
    </main>
    <?php if (!empty($checkout_id) && empty($order)) : ?>
        <script>
            window.setInterval(function () {
                fetch(<?= json_encode(base_url('payment/payment_status?checkout_id='.rawurlencode($checkout_id))) ?>)
                    .then(function (response) { return response.json(); })
                    .then(function (status) {
                        if (status.paid) {
                            window.location.reload();
                        }
                    });
            }, 3000);
        </script>
    <?php endif; ?>
</body>
</html>