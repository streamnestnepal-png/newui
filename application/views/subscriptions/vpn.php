<style>
    .vpn-page { padding: 52px 15px 30px; }
    .vpn-hero { padding: 42px 28px; margin-bottom: 38px; background: linear-gradient(120deg, #082f49, #0f766e); color: #fff; }
    .vpn-hero h1 { margin: 0 0 10px; color: #fff; font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 700; }
    .vpn-hero p { max-width: 640px; margin: 0; color: #ccfbf1; }
    .vpn-art { position: relative; display: grid; width: 100%; height: 180px; place-items: center; overflow: hidden; background: #e0f2fe; }
    .vpn-image { display: block; width: 100%; height: 100%; object-fit: cover; }
    .vpn-badge, .vpn-country { position: absolute; z-index: 2; padding: 4px 8px; font-size: 11px; font-weight: 700; }
    .vpn-badge { top: 10px; left: 10px; background: #0f766e; color: #fff; }
    .vpn-country { right: 10px; bottom: 10px; background: #fff; color: #111827; }
    .vpn-brand { margin: -25px 0 6px; color: #0f766e; font-size: 12px; font-weight: 700; }
    .vpn-name { min-height: 82px; margin: -4px 0 12px; color: #111827; font-size: 16px; font-weight: 700; line-height: 1.4; }
    .vpn-from { margin: 0; color: #6b7280; font-size: 12px; }
    .vpn-price { margin: 2px 0 14px; color: #111827; font-size: 24px; font-weight: 700; }
    .vpn-buy, .subscription-add { cursor: pointer; }
    .subscription-add { margin-top: 8px; animation: subscriptionAdd .35s ease both; }
    @keyframes subscriptionAdd { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    @media (min-width: 768px) { .vpn-page .card-game { width: 100% !important; height: auto; min-height: 445px; margin-left: 0 !important; } }
    @media (max-width: 767px) {
        .vpn-page { padding: 30px 15px 20px; }
        .vpn-hero { padding: 30px 20px; margin-bottom: 28px; }
        .vpn-hero h1 { font-size: 2rem; }
        .vpn-page > .d-flex { display: block !important; }
        .vpn-page > .d-flex small { display: block; margin-top: 10px; }
        .vpn-page .row { margin-right: -15px; margin-left: -15px; }
        .vpn-page .col-md-3 { width: 100%; padding-right: 15px; padding-left: 15px; }
        .vpn-page .card-game { width: 100% !important; height: auto; min-height: 350px; margin-right: 0; }
        .vpn-name { min-height: 0; font-size: 16px; }
    }
</style>

<main class="container vpn-page">
    <section class="vpn-hero">
        <p class="text-uppercase font-weight-bold mb-2" style="color:#99f6e4; letter-spacing:2px;">Private browsing</p>
        <h1>VPN Plans</h1>
        <p>Choose a VPN service for your devices and pay in Nepalese rupees.</p>
    </section>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="title mb-0">VPN Plans</h2>
        <small class="text-muted">All prices shown in Nepalese rupees</small>
    </div>

    <section class="row" aria-label="VPN plans">
        <?php foreach ($products as $index => $product) : ?>
            <div class="col-md-3 mt-4" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="<?= $index * 50 ?>">
                <div class="card card-game subscription-product" style="width:16rem;">
                    <div class="card-img-top vpn-art">
                        <span class="vpn-badge">VPN</span>
                        <img class="vpn-image" src="<?= base_url($product[3]) ?>" alt="<?= html_escape($product[0]) ?>">
                        <span class="vpn-country"><?= html_escape($product[1]) ?></span>
                    </div>
                    <div class="card-body">
                        <p class="vpn-brand">VPN Service</p>
                        <h3 class="vpn-name"><?= html_escape($product[0]) ?></h3>
                        <p class="vpn-from">Price</p>
                        <p class="vpn-price">NPR <?= number_format($product[2]) ?></p>
                        <button class="btn btn-primary btn-block btn-games font-weight-bold vpn-buy subscription-buy" type="button" hidden>Buy Now &rarr;</button>
                        <button class="btn btn-outline-primary btn-block font-weight-bold subscription-add" type="button" hidden data-product="<?= html_escape($product[0]) ?>" data-package="VPN" data-price="NPR <?= number_format($product[2]) ?>" data-checkout-page="<?= base_url('subscriptions/checkout') ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<script src="<?= base_url('assets/js/subscription-cart.js') ?>"></script>