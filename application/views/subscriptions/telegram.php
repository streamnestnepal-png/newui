<style>
    .telegram-page { padding: 52px 15px 30px; }
    .telegram-hero { padding: 42px 28px; margin-bottom: 38px; background: linear-gradient(120deg, #075985, #0ea5e9); color: #fff; }
    .telegram-hero h1 { margin: 0 0 10px; color: #fff; font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 700; }
    .telegram-hero p { max-width: 640px; margin: 0; color: #e0f2fe; }
    .telegram-art { position: relative; display: grid; width: 100%; height: 180px; place-items: center; overflow: hidden; background: #e0f2fe; }
    .telegram-image { display: block; width: 100%; height: 100%; object-fit: cover; }
    .telegram-badge, .telegram-country { position: absolute; z-index: 2; padding: 4px 8px; font-size: 11px; font-weight: 700; }
    .telegram-badge { top: 10px; left: 10px; background: #0284c7; color: #fff; }
    .telegram-country { right: 10px; bottom: 10px; background: #fff; color: #111827; }
    .telegram-brand { margin: -25px 0 6px; color: #0284c7; font-size: 12px; font-weight: 700; }
    .telegram-name { min-height: 82px; margin: -4px 0 12px; color: #111827; font-size: 16px; font-weight: 700; line-height: 1.4; }
    .telegram-from { margin: 0; color: #6b7280; font-size: 12px; }
    .telegram-price { margin: 2px 0 14px; color: #111827; font-size: 24px; font-weight: 700; }
    .telegram-buy, .subscription-add { cursor: pointer; }
    .subscription-add { margin-top: 8px; animation: subscriptionAdd .35s ease both; }
    @keyframes subscriptionAdd { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    @media (min-width: 768px) { .telegram-page .card-game { width: 100% !important; height: auto; min-height: 445px; margin-left: 0 !important; } }
    @media (max-width: 767px) {
        .telegram-page { padding: 30px 15px 20px; }
        .telegram-hero { padding: 30px 20px; margin-bottom: 28px; }
        .telegram-hero h1 { font-size: 2rem; }
        .telegram-page > .d-flex { display: block !important; }
        .telegram-page > .d-flex small { display: block; margin-top: 10px; }
        .telegram-page .row { margin-right: -15px; margin-left: -15px; }
        .telegram-page .col-md-3 { width: 100%; padding-right: 15px; padding-left: 15px; }
        .telegram-page .card-game { width: 100% !important; height: auto; min-height: 350px; margin-right: 0; }
        .telegram-name { min-height: 0; font-size: 16px; }
    }
</style>

<main class="container telegram-page">
    <section class="telegram-hero">
        <p class="text-uppercase font-weight-bold mb-2" style="color:#bae6fd; letter-spacing:2px;">Messaging subscriptions</p>
        <h1>Telegram Plans</h1>
        <p>Choose Telegram Stars, Premium, or Members plans and pay in Nepalese rupees.</p>
    </section>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="title mb-0">Telegram Plans</h2>
        <small class="text-muted">All prices shown in Nepalese rupees</small>
    </div>

    <section class="row" aria-label="Telegram plans">
        <?php foreach ($products as $index => $product) : ?>
            <div class="col-md-3 mt-4" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="<?= $index * 50 ?>">
                <div class="card card-game subscription-product" style="width:16rem;">
                    <div class="card-img-top telegram-art">
                        <span class="telegram-badge">TELEGRAM</span>
                        <img class="telegram-image" src="<?= base_url('media/telegram.avif') ?>" alt="Telegram">
                        <span class="telegram-country"><?= html_escape($product[1]) ?></span>
                    </div>
                    <div class="card-body">
                        <p class="telegram-brand">Telegram</p>
                        <h3 class="telegram-name"><?= html_escape($product[0]) ?></h3>
                        <p class="telegram-from">Price</p>
                        <p class="telegram-price">NPR <?= number_format($product[2]) ?></p>
                        <button class="btn btn-primary btn-block btn-games font-weight-bold telegram-buy subscription-buy" type="button" hidden>Buy Now &rarr;</button>
                        <button class="btn btn-outline-primary btn-block font-weight-bold subscription-add" type="button" hidden data-product="<?= html_escape($product[0]) ?>" data-package="Telegram" data-price="NPR <?= number_format($product[2]) ?>" data-checkout-page="<?= base_url('subscriptions/checkout') ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<script src="<?= base_url('assets/js/subscription-cart.js') ?>"></script>