<style>
    .spotify-page { padding: 52px 15px 35px; }
    .spotify-hero { padding: 42px 28px; margin-bottom: 38px; background: linear-gradient(120deg, #075c39, #1ed760); color: #fff; animation: spotifyReveal .55s ease both; }
    .spotify-hero h1 { margin: 0 0 10px; color: #fff; font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 700; }
    .spotify-hero p { max-width: 640px; margin: 0; color: #e1ffed; }
    .spotify-art { position: relative; display: grid; width: 100%; height: auto; aspect-ratio: 1 / 1; place-items: center; overflow: hidden; background: #20d477; }
    .spotify-image { display: block; width: 100%; height: 100%; object-fit: cover; }
    .spotify-country { position: absolute; right: 10px; bottom: 10px; z-index: 2; padding: 4px 8px; background: #fff; color: #211052; font-size: 11px; font-weight: 700; }
    .spotify-brand { margin: -25px 0 6px; color: #075c39; font-size: 12px; font-weight: 700; }
    .spotify-name { min-height: 58px; margin: -4px 0 12px; color: #211052; font-size: 17px; font-weight: 700; line-height: 1.4; }
    .spotify-from { margin: 0; color: #716982; font-size: 12px; }
    .spotify-price { margin: 2px 0 14px; color: #211052; font-size: 24px; font-weight: 700; }
    .spotify-buy, .subscription-add { cursor: pointer; }
    .subscription-add { margin-top: 8px; animation: subscriptionAdd .35s ease both; }
    @keyframes subscriptionAdd { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes spotifyReveal { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }
    @media (min-width: 768px) { .spotify-page .card-game { width: 100% !important; height: auto; min-height: 440px; margin-left: 0 !important; } }
    @media (max-width: 767px) {
        .spotify-page { width: 100%; padding: 30px 15px 20px; }
        .spotify-hero { padding: 30px 20px; margin-bottom: 28px; }
        .spotify-hero h1 { font-size: 2rem; }
        .spotify-page > .d-flex { display: block !important; }
        .spotify-page > .d-flex small { display: block; margin-top: 10px; }
        .spotify-page .row { margin-right: -15px; margin-left: -15px; }
        .spotify-page .col-md-3 { width: 100%; padding-right: 15px; padding-left: 15px; }
        .spotify-page .card-game { width: 100% !important; height: auto; min-height: 350px; margin-right: 0; }
        .spotify-name { min-height: 0; font-size: 16px; }
    }
</style>

<main class="container spotify-page">
    <section class="spotify-hero" data-aos="fade-right" data-aos-duration="1200">
        <p class="text-uppercase font-weight-bold mb-2" style="color:#d5ffe4; letter-spacing:2px;">Premium subscriptions</p>
        <h1>Spotify Premium</h1>
        <p>Enjoy music without limits with fast, secure, and affordable access.</p>
    </section>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="title mb-0">Spotify Plans</h2>
        <small class="text-muted">All prices shown in Nepalese rupees</small>
    </div>

    <section class="row" aria-label="Spotify plans">
        <?php foreach ($products as $index => $product) : ?>
            <div class="col-md-3 mt-4" data-aos="fade-right" data-aos-duration="1500" data-aos-delay="<?= $index * 80 ?>">
                <div class="card card-game subscription-product" style="width:16rem;">
                    <div class="card-img-top spotify-art">
                        <img class="spotify-image" src="<?= base_url('media/spotify.jpg') ?>" alt="Spotify Premium">
                        <span class="spotify-country"><?= html_escape($product[1]) ?></span>
                    </div>
                    <div class="card-body">
                        <p class="spotify-brand">Spotify</p>
                        <h3 class="spotify-name"><?= html_escape($product[0]) ?></h3>
                        <p class="spotify-from">From</p>
                        <p class="spotify-price">NPR <?= number_format($product[2]) ?></p>
                        <button class="btn btn-danger btn-block btn-games font-weight-bold spotify-buy subscription-buy" type="button" hidden>Buy Now &rarr;</button>
                        <button class="btn btn-outline-danger btn-block font-weight-bold subscription-add" type="button" hidden data-product="<?= html_escape($product[0]) ?>" data-package="Spotify Premium" data-price="NPR <?= number_format($product[2]) ?>" data-checkout-page="<?= base_url('subscriptions/checkout') ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<script src="<?= base_url('assets/js/subscription-cart.js') ?>"></script>
