<style>
    .adobe-page {
        padding: 52px 15px 30px;
    }

    .adobe-hero {
        padding: 42px 28px;
        margin-bottom: 38px;
        background: linear-gradient(120deg, #111827, #e11d48);
        color: #fff;
        animation: adobeReveal .55s ease both;
    }

    .adobe-hero h1 {
        margin: 0 0 10px;
        color: #fff;
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 700;
    }

    .adobe-hero p {
        max-width: 640px;
        margin: 0;
        color: #ffe4e6;
    }

    .adobe-art {
        position: relative;
        display: grid;
        width: 100%;
        aspect-ratio: 1 / 1;
        height: auto;
        place-items: center;
        overflow: hidden;
        background: #111827;
    }

    .adobe-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .adobe-badge,
    .adobe-country {
        position: absolute;
        z-index: 2;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 700;
    }

    .adobe-badge {
        top: 10px;
        left: 10px;
        background: #e11d48;
        color: #fff;
    }

    .adobe-country {
        right: 10px;
        bottom: 10px;
        background: #fff;
        color: #111827;
    }

    .adobe-brand {
        margin: -25px 0 6px;
        color: #dc2626;
        font-size: 12px;
        font-weight: 700;
    }

    .adobe-name {
        min-height: 58px;
        margin: -4px 0 12px;
        color: #111827;
        font-size: 17px;
        font-weight: 700;
        line-height: 1.4;
    }

    .adobe-from {
        margin: 0;
        color: #6b7280;
        font-size: 12px;
    }

    .adobe-price {
        margin: 2px 0 14px;
        color: #111827;
        font-size: 24px;
        font-weight: 700;
    }

    .adobe-buy, .subscription-add { cursor: pointer; }
    .subscription-add { margin-top: 8px; animation: subscriptionAdd .35s ease both; }
    @keyframes subscriptionAdd { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

    @keyframes adobeReveal { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }

    @media (min-width: 768px) {
        .adobe-page .card-game {
            width: 100% !important;
            height: auto;
            min-height: 440px;
            margin-left: 0 !important;
        }
    }

    @media (max-width: 767px) {
        .adobe-page {
            width: 100%;
            padding: 30px 15px 20px;
        }

        .adobe-hero {
            padding: 30px 20px;
            margin-bottom: 28px;
        }

        .adobe-hero h1 {
            font-size: 2rem;
        }

        .adobe-page > .d-flex {
            display: block !important;
        }

        .adobe-page > .d-flex small {
            display: block;
            margin-top: 10px;
        }

        .adobe-page .row {
            margin-right: -15px;
            margin-left: -15px;
        }

        .adobe-page .col-md-3 {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

        .adobe-page .card-game {
            width: 100% !important;
            height: auto;
            min-height: 350px;
            margin-right: 0;
        }

        .adobe-art {
            height: 180px;
        }

        .adobe-name {
            min-height: 0;
            font-size: 16px;
        }
    }
</style>

<main class="container adobe-page">
    <section class="adobe-hero" data-aos="fade-right" data-aos-duration="1200">
        <p class="text-uppercase font-weight-bold mb-2" style="color:#fbcfe8; letter-spacing:2px;">Creative subscriptions</p>
        <h1>Adobe Creative Cloud Plans</h1>
        <p>Get Adobe Creative Cloud Pro with the latest pricing and easy checkout in Nepalese rupees.</p>
    </section>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="title mb-0">Adobe Plans</h2>
        <small class="text-muted">All prices shown in Nepalese rupees</small>
    </div>

    <section class="row" aria-label="Adobe plans">
        <?php foreach ($products as $index => $product) : ?>
            <div class="col-md-3 mt-4" data-aos="fade-right" data-aos-duration="1500" data-aos-delay="<?= $index * 80 ?>">
                <div class="card card-game subscription-product" style="width:16rem;">
                    <div class="card-img-top adobe-art">
                        <span class="adobe-badge">ADOBE</span>
                        <img class="adobe-image" src="<?= base_url('media/adobe.jpeg') ?>" alt="Adobe Creative Cloud">
                        <span class="adobe-country"><?= html_escape($product[1]) ?></span>
                    </div>
                    <div class="card-body">
                        <p class="adobe-brand">Adobe</p>
                        <h3 class="adobe-name"><?= html_escape($product[0]) ?></h3>
                        <p class="adobe-from">From</p>
                        <p class="adobe-price">NPR <?= number_format($product[2]) ?></p>
                        <button class="btn btn-danger btn-block btn-games font-weight-bold adobe-buy subscription-buy" type="button" hidden>Buy Now &rarr;</button>
                        <button class="btn btn-outline-danger btn-block font-weight-bold subscription-add" type="button" hidden data-product="<?= html_escape($product[0]) ?>" data-package="Adobe Creative Cloud Pro" data-price="NPR <?= number_format($product[2]) ?>" data-checkout-page="<?= base_url('subscriptions/checkout') ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<script src="<?= base_url('assets/js/subscription-cart.js') ?>"></script>
