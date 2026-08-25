<style>
    .netflix-page {
        padding: 52px 15px 30px;
    }

    .netflix-hero {
        padding: 42px 28px;
        margin-bottom: 38px;
        background: linear-gradient(120deg, #211052, #4018a5);
        color: #fff;
        animation: netflixReveal .55s ease both;
    }

    .netflix-hero h1 {
        margin: 0 0 10px;
        color: #fff;
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 700;
    }

    .netflix-hero p {
        max-width: 640px;
        margin: 0;
        color: #e9e1ff;
    }

    .netflix-art {
        position: relative;
        display: grid;
        width: 100%;
        aspect-ratio: 1 / 1;
        height: auto;
        place-items: center;
        overflow: hidden;
        background: #080808;
    }

    .netflix-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .netflix-badge,
    .netflix-country {
        position: absolute;
        z-index: 2;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 700;
    }

    .netflix-badge {
        top: 10px;
        left: 10px;
        background: #e50914;
        color: #fff;
    }

    .netflix-country {
        right: 10px;
        bottom: 10px;
        background: #fff;
        color: #211052;
    }

    .netflix-brand {
        margin: -25px 0 6px;
        color: #ff5962;
        font-size: 12px;
        font-weight: 700;
    }

    .netflix-name {
        min-height: 58px;
        margin: -4px 0 12px;
        color: #211052;
        font-size: 17px;
        font-weight: 700;
        line-height: 1.4;
    }

    .netflix-from {
        margin: 0;
        color: #716982;
        font-size: 12px;
    }

    .netflix-price {
        margin: 2px 0 14px;
        color: #211052;
        font-size: 24px;
        font-weight: 700;
    }

    .netflix-buy, .subscription-add { cursor: pointer; }
    .subscription-add { margin-top: 8px; animation: subscriptionAdd .35s ease both; }
    @keyframes subscriptionAdd { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

    @media (min-width: 768px) {
        .netflix-page .card-game {
            width: 100% !important;
            height: auto;
            min-height: 440px;
            margin-left: 0 !important;
        }
    }

    @media (max-width: 767px) {
        .netflix-page {
            width: 100%;
            padding: 30px 15px 20px;
        }

        .netflix-hero {
            padding: 30px 20px;
            margin-bottom: 28px;
        }

        .netflix-hero h1 {
            font-size: 2rem;
        }

        .netflix-page > .d-flex {
            display: block !important;
        }

        .netflix-page > .d-flex small {
            display: block;
            margin-top: 10px;
        }

        .netflix-page .row {
            margin-right: -15px;
            margin-left: -15px;
        }

        .netflix-page .col-md-3 {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

        .netflix-page .card-game {
            width: 100% !important;
            height: auto;
            min-height: 350px;
            margin-right: 0;
        }

        .netflix-art {
            height: 180px;
        }

        .netflix-name {
            min-height: 0;
            font-size: 16px;
        }
    }
</style>

<main class="container netflix-page">
    <section class="netflix-hero" data-aos="fade-right" data-aos-duration="1200">
        <p class="text-uppercase font-weight-bold mb-2" style="color:#9fffc9; letter-spacing:2px;">Premium subscriptions</p>
        <h1>Netflix Subscription Plans</h1>
        <p>Choose your region, pay in Nepalese rupees, and enjoy your favorite entertainment.</p>
    </section>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="title mb-0">Netflix Plans</h2>
        <small class="text-muted">All prices shown in Nepalese rupees</small>
    </div>

    <section class="row" aria-label="Netflix plans">
        <?php foreach ($products as $index => $product) : ?>
            <div class="col-md-3 mt-4" data-aos="fade-right" data-aos-duration="1500" data-aos-delay="<?= $index * 80 ?>">
                <div class="card card-game subscription-product" style="width:16rem;">
                    <div class="card-img-top netflix-art">
                        <span class="netflix-badge">NETFLIX</span>
                        <img class="netflix-image" src="<?= base_url('media/netflix.jpg') ?>" alt="Netflix">
                        <span class="netflix-country"><?= html_escape($product[1]) ?></span>
                    </div>
                    <div class="card-body">
                        <p class="netflix-brand">Netflix</p>
                        <h3 class="netflix-name"><?= html_escape($product[0]) ?></h3>
                        <p class="netflix-from">From</p>
                        <p class="netflix-price">NPR <?= number_format($product[2]) ?></p>
                        <button class="btn btn-danger btn-block btn-games font-weight-bold netflix-buy subscription-buy" type="button" hidden>Buy Now &rarr;</button>
                        <button class="btn btn-outline-danger btn-block font-weight-bold subscription-add" type="button" hidden data-product="<?= html_escape($product[0]) ?>" data-package="Netflix Subscription" data-price="NPR <?= number_format($product[2]) ?>" data-checkout-page="<?= base_url('subscriptions/checkout') ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<script src="<?= base_url('assets/js/subscription-cart.js') ?>"></script>
