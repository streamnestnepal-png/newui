<style>
    .youtube-page {
        padding: 52px 15px 30px;
    }

    .youtube-hero {
        padding: 42px 28px;
        margin-bottom: 38px;
        background: linear-gradient(120deg, #111827, #ff0000);
        color: #fff;
        animation: youtubeReveal .55s ease both;
    }

    .youtube-hero h1 {
        margin: 0 0 10px;
        color: #fff;
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 700;
    }

    .youtube-hero p {
        max-width: 640px;
        margin: 0;
        color: #ffe4e6;
    }

    .youtube-art {
        position: relative;
        display: grid;
        width: 100%;
        aspect-ratio: 1 / 1;
        height: auto;
        place-items: center;
        overflow: hidden;
        background: #0f172a;
    }

    .youtube-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .youtube-badge,
    .youtube-country {
        position: absolute;
        z-index: 2;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 700;
    }

    .youtube-badge {
        top: 10px;
        left: 10px;
        background: #ff0000;
        color: #fff;
    }

    .youtube-country {
        right: 10px;
        bottom: 10px;
        background: #fff;
        color: #111827;
    }

    .youtube-brand {
        margin: -25px 0 6px;
        color: #ff2d2d;
        font-size: 12px;
        font-weight: 700;
    }

    .youtube-name {
        min-height: 58px;
        margin: -4px 0 12px;
        color: #111827;
        font-size: 17px;
        font-weight: 700;
        line-height: 1.4;
    }

    .youtube-from {
        margin: 0;
        color: #6b7280;
        font-size: 12px;
    }

    .youtube-price {
        margin: 2px 0 14px;
        color: #111827;
        font-size: 24px;
        font-weight: 700;
    }

    .youtube-buy, .subscription-add { cursor: pointer; }
    .subscription-add { margin-top: 8px; animation: subscriptionAdd .35s ease both; }
    @keyframes subscriptionAdd { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

    @keyframes youtubeReveal { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }

    @media (min-width: 768px) {
        .youtube-page .card-game {
            width: 100% !important;
            height: auto;
            min-height: 440px;
            margin-left: 0 !important;
        }
    }

    @media (max-width: 767px) {
        .youtube-page {
            width: 100%;
            padding: 30px 15px 20px;
        }

        .youtube-hero {
            padding: 30px 20px;
            margin-bottom: 28px;
        }

        .youtube-hero h1 {
            font-size: 2rem;
        }

        .youtube-page > .d-flex {
            display: block !important;
        }

        .youtube-page > .d-flex small {
            display: block;
            margin-top: 10px;
        }

        .youtube-page .row {
            margin-right: -15px;
            margin-left: -15px;
        }

        .youtube-page .col-md-3 {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

        .youtube-page .card-game {
            width: 100% !important;
            height: auto;
            min-height: 350px;
            margin-right: 0;
        }

        .youtube-art {
            height: 180px;
        }

        .youtube-name {
            min-height: 0;
            font-size: 16px;
        }
    }
</style>

<main class="container youtube-page">
    <section class="youtube-hero" data-aos="fade-right" data-aos-duration="1200">
        <p class="text-uppercase font-weight-bold mb-2" style="color:#fcd34d; letter-spacing:2px;">Premium subscriptions</p>
        <h1>YouTube Premium Plans</h1>
        <p>Choose the plan that fits you and pay in Nepalese rupees for a smooth premium experience.</p>
    </section>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="title mb-0">YouTube Plans</h2>
        <small class="text-muted">All prices shown in Nepalese rupees</small>
    </div>

    <section class="row" aria-label="YouTube plans">
        <?php foreach ($products as $index => $product) : ?>
            <div class="col-md-3 mt-4" data-aos="fade-right" data-aos-duration="1500" data-aos-delay="<?= $index * 80 ?>">
                <div class="card card-game subscription-product" style="width:16rem;">
                    <div class="card-img-top youtube-art">
                        <span class="youtube-badge">YOUTUBE</span>
                        <img class="youtube-image" src="<?= base_url('media/youtube.jpg') ?>" alt="YouTube Premium">
                        <span class="youtube-country"><?= html_escape($product[1]) ?></span>
                    </div>
                    <div class="card-body">
                        <p class="youtube-brand">YouTube</p>
                        <h3 class="youtube-name"><?= html_escape($product[0]) ?></h3>
                        <p class="youtube-from">From</p>
                        <p class="youtube-price">NPR <?= number_format($product[2]) ?></p>
                        <button class="btn btn-danger btn-block btn-games font-weight-bold youtube-buy subscription-buy" type="button" hidden>Buy Now &rarr;</button>
                        <button class="btn btn-outline-danger btn-block font-weight-bold subscription-add" type="button" hidden data-product="<?= html_escape($product[0]) ?>" data-package="YouTube Premium" data-price="NPR <?= number_format($product[2]) ?>" data-checkout-page="<?= base_url('subscriptions/checkout') ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<script src="<?= base_url('assets/js/subscription-cart.js') ?>"></script>
