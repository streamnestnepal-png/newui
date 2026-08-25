<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orders | StreamNest</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>css/styling.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="settings-page">
    <main class="settings-card settings-card-wide">
        <a class="login-back-link" href="<?=base_url('user/settings');?>">&larr; Back to settings</a>
        <p class="login-kicker">PURCHASES</p>
        <h1>Order history</h1>
        <?php if (!$orders) : ?>
            <div class="empty-orders"><i class="fas fa-box-open"></i><h2>No orders yet</h2><p>Your completed and pending orders will appear here.</p></div>
        <?php else : ?>
            <div class="order-list">
                <?php foreach ($orders as $order) : ?>
                    <article class="order-row">
                        <div><p class="order-product"><?=htmlspecialchars($order['product'], ENT_QUOTES, 'UTF-8');?></p><p class="order-package"><?=htmlspecialchars($order['package'], ENT_QUOTES, 'UTF-8');?></p><small><?=htmlspecialchars($order['order_id'], ENT_QUOTES, 'UTF-8');?></small></div>
                        <div class="text-right"><strong>NPR <?=number_format(((int) $order['amount']) / 100, 2);?></strong><span class="order-status"><?=htmlspecialchars(ucfirst($order['status']), ENT_QUOTES, 'UTF-8');?></span></div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
