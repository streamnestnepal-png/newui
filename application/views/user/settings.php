<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Settings | StreamNest</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>css/styling.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="settings-page">
    <main class="settings-card settings-card-wide">
        <a class="login-back-link" href="<?=base_url('welcome');?>">&larr; Back to home</a>
        <p class="login-kicker">ACCOUNT</p>
        <div class="settings-heading">
            <div>
                <h1><?=htmlspecialchars($user['nama'] ?? 'Your account', ENT_QUOTES, 'UTF-8');?></h1>
                <p class="settings-email"><?=htmlspecialchars($email, ENT_QUOTES, 'UTF-8');?></p>
            </div>
            <div class="settings-avatar"><i class="fas fa-user"></i></div>
        </div>
        <div class="settings-grid">
            <section class="settings-section">
                <p class="settings-section-label">PROFILE</p>
                <h2>Account details</h2>
                <div class="settings-detail"><span>Full name</span><strong><?=htmlspecialchars($user['nama'] ?? 'Not available', ENT_QUOTES, 'UTF-8');?></strong></div>
                <div class="settings-detail"><span>Email</span><strong><?=htmlspecialchars($email, ENT_QUOTES, 'UTF-8');?></strong></div>
            </section>
            <section class="settings-section settings-order-section">
                <p class="settings-section-label">LATEST ORDER</p>
                <?php if ($latest_order) : ?>
                    <h2><?=htmlspecialchars($latest_order['product'], ENT_QUOTES, 'UTF-8');?></h2>
                    <p><?=htmlspecialchars($latest_order['package'], ENT_QUOTES, 'UTF-8');?></p>
                    <div class="settings-order-meta"><strong><?=htmlspecialchars($latest_order['order_id'], ENT_QUOTES, 'UTF-8');?></strong><span class="order-status"><?=htmlspecialchars(ucfirst($latest_order['status']), ENT_QUOTES, 'UTF-8');?></span></div>
                <?php else : ?>
                    <h2>No orders yet</h2>
                    <p>Your latest order will appear here.</p>
                <?php endif; ?>
                <a class="settings-orders-link" href="<?=base_url('user/orders');?>">View all orders &rarr;</a>
            </section>
        </div>
        <div class="settings-actions">
            <a class="btn btn-modal" href="<?=base_url('welcome/logout');?>"><i class="fas fa-sign-out-alt mr-2"></i> Log out</a>
            <a class="btn btn-outline-dark" href="<?=base_url('welcome');?>">Continue shopping</a>
        </div>
    </main>
</body>
</html>
