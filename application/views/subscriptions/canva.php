<style>
    .canva-page {
        padding: 52px 15px 30px;
    }

    .canva-hero {
        padding: 42px 28px;
        margin-bottom: 38px;
        background: linear-gradient(120deg, #0f172a, #7c3aed);
        color: #fff;
        animation: canvaReveal .55s ease both;
    }

    .canva-hero h1 {
        margin: 0 0 10px;
        color: #fff;
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 700;
    }

    .canva-hero p {
        max-width: 640px;
        margin: 0;
        color: #e9d5ff;
    }

    .canva-art {
        position: relative;
        display: grid;
        width: 100%;
        aspect-ratio: 1 / 1;
        height: auto;
        place-items: center;
        overflow: hidden;
        background: #f3e8ff;
    }

    .canva-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .canva-badge,
    .canva-country {
        position: absolute;
        z-index: 2;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 700;
    }

    .canva-badge {
        top: 10px;
        left: 10px;
        background: #7c3aed;
        color: #fff;
    }

    .canva-country {
        right: 10px;
        bottom: 10px;
        background: #fff;
        color: #111827;
    }

    .canva-brand {
        margin: -25px 0 6px;
        color: #7c3aed;
        font-size: 12px;
        font-weight: 700;
    }

    .canva-name {
        min-height: 58px;
        margin: -4px 0 12px;
        color: #111827;
        font-size: 17px;
        font-weight: 700;
        line-height: 1.4;
    }

    .canva-from {
        margin: 0;
        color: #6b7280;
        font-size: 12px;
    }

    .canva-price {
        margin: 2px 0 14px;
        color: #111827;
        font-size: 24px;
        font-weight: 700;
    }

    .canva-buy, .subscription-add { cursor: pointer; }
    .subscription-add { margin-top: 8px; animation: subscriptionAdd .35s ease both; }
    @keyframes subscriptionAdd { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

    @keyframes canvaReveal { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }

    @media (min-width: 768px) {
        .canva-page .card-game {
            width: 100% !important;
            height: auto;
            min-height: 440px;
            margin-left: 0 !important;
        }
    }

    @media (max-width: 767px) {
        .canva-page {
            width: 100%;
            padding: 30px 15px 20px;
        }

        .canva-hero {
            padding: 30px 20px;
            margin-bottom: 28px;
        }

        .canva-hero h1 {
            font-size: 2rem;
        }

        .canva-page > .d-flex {
            display: block !important;
        }

        .canva-page > .d-flex small {
            display: block;
            margin-top: 10px;
        }

        .canva-page .row {
            margin-right: -15px;
            margin-left: -15px;
        }

        .canva-page .col-md-3 {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

        .canva-page .card-game {
            width: 100% !important;
            height: auto;
            min-height: 350px;
            margin-right: 0;
        }

        .canva-art {
            height: 180px;
        }

        .canva-name {
            min-height: 0;
            font-size: 16px;
        }
    }
</style>

<main class="container canva-page">
    <section class="canva-hero" data-aos="fade-right" data-aos-duration="1200">
        <p class="text-uppercase font-weight-bold mb-2" style="color:#ddd6fe; letter-spacing:2px;">Design subscriptions</p>
        <h1>Canva Plans</h1>
        <p>Choose the plan that fits your design work and pay in Nepalese rupees.</p>
    </section>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="title mb-0">Canva Plans</h2>
        <small class="text-muted">All prices shown in Nepalese rupees</small>
    </div>

    <section class="row" aria-label="Canva plans">
        <?php foreach ($products as $index => $product) : ?>
            <div class="col-md-3 mt-4" data-aos="fade-right" data-aos-duration="1500" data-aos-delay="<?= $index * 80 ?>">
                <div class="card card-game subscription-product" style="width:16rem;">
                    <div class="card-img-top canva-art">
                        <span class="canva-badge">CANVA</span>
                        <img class="canva-image" src="<?= base_url('media/canva.avif') ?>" alt="Canva">
                        <span class="canva-country"><?= html_escape($product[1]) ?></span>
                    </div>
                    <div class="card-body">
                        <p class="canva-brand">Canva</p>
                        <h3 class="canva-name"><?= html_escape($product[0]) ?></h3>
                        <p class="canva-from">From</p>
                        <p class="canva-price">NPR <?= number_format($product[2]) ?></p>
                        <button class="btn btn-primary btn-block btn-games font-weight-bold canva-buy subscription-buy" type="button" hidden>Buy Now &rarr;</button>
                        <button class="btn btn-outline-primary btn-block font-weight-bold subscription-add" type="button" hidden data-product="<?= html_escape($product[0]) ?>" data-package="Canva" data-price="NPR <?= number_format($product[2]) ?>" data-checkout-page="<?= base_url('subscriptions/checkout') ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<script src="<?= base_url('assets/js/subscription-cart.js') ?>"></script>
