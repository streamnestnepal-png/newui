<style>
    .all-page { padding: 52px 15px 30px; }
    .all-hero { padding: 42px 28px; margin-bottom: 38px; background: linear-gradient(120deg, #172554, #0f766e); color: #fff; }
    .all-hero h1 { margin: 0 0 10px; color: #fff; font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 700; }
    .all-hero p { max-width: 640px; margin: 0; color: #dbeafe; }
    .all-art { position: relative; display: grid; width: 100%; height: 180px; place-items: center; overflow: hidden; background: #f3f4f6; }
    .all-image { display: block; width: 100%; height: 100%; object-fit: cover; }
    .all-badge, .all-country { position: absolute; z-index: 2; padding: 4px 8px; font-size: 11px; font-weight: 700; }
    .all-badge { top: 10px; left: 10px; background: #0f766e; color: #fff; }
    .all-country { right: 10px; bottom: 10px; background: #fff; color: #111827; }
    .all-name { min-height: 82px; margin: 0 0 12px; color: #111827; font-size: 16px; font-weight: 700; line-height: 1.4; }
    .all-price { margin: 2px 0 14px; color: #111827; font-size: 24px; font-weight: 700; }
    .all-buy, .subscription-add { cursor: pointer; }
    .subscription-add { margin-top: 8px; animation: subscriptionAdd .35s ease both; }
    @keyframes subscriptionAdd { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    .all-search { max-width: 420px; margin-bottom: 18px; }
    .all-search input { height: 44px; border: 1px solid #d1d5db; border-radius: 4px; padding: 0 14px; }
    .all-filters { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 12px; white-space: nowrap; }
    .all-filter { flex: 0 0 auto; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px 14px; background: #fff; color: #374151; cursor: pointer; }
    .all-filter.active, .all-filter:hover { border-color: #0f766e; background: #0f766e; color: #fff; }
    .all-empty { display: none; width: 100%; padding: 24px 15px; color: #6b7280; text-align: center; }
    @media (min-width: 768px) { .all-page .card-game { width: 100% !important; height: auto; min-height: 410px; margin-left: 0 !important; } }
    @media (max-width: 767px) {
        .all-page { padding: 30px 15px 20px; }
        .all-hero { padding: 30px 20px; margin-bottom: 28px; }
        .all-hero h1 { font-size: 2rem; }
        .all-page > .d-flex { display: block !important; }
        .all-page > .d-flex small { display: block; margin-top: 10px; }
        .all-page .row { margin-right: -15px; margin-left: -15px; }
        .all-page .col-md-3 { width: 100%; padding-right: 15px; padding-left: 15px; }
        .all-page .card-game { width: 100% !important; height: auto; min-height: 350px; margin-right: 0; }
        .all-name { min-height: 0; }
    }
</style>

<main class="container all-page">
    <section class="all-hero">
        <p class="text-uppercase font-weight-bold mb-2" style="color:#ccfbf1; letter-spacing:2px;">Complete catalog</p>
        <h1>All Subscriptions</h1>
        <p>Browse every subscription and digital service currently available in Nepalese rupees.</p>
    </section>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="title mb-0">All Plans</h2>
        <small class="text-muted"><?= count($products) ?> plans available</small>
    </div>

    <div class="all-search">
        <input class="form-control" id="all-search-input" type="search" placeholder="Search subscriptions..." aria-label="Search subscriptions">
    </div>
    <div class="all-filters" aria-label="Subscription categories">
        <button class="all-filter active" type="button" data-filter="">All Plans</button>
        <button class="all-filter" type="button" data-filter="Netflix">Netflix</button>
        <button class="all-filter" type="button" data-filter="Spotify">Spotify</button>
        <button class="all-filter" type="button" data-filter="YouTube">YouTube</button>
        <button class="all-filter" type="button" data-filter="ChatGPT">ChatGPT</button>
        <button class="all-filter" type="button" data-filter="Canva">Canva</button>
        <button class="all-filter" type="button" data-filter="Adobe">Adobe</button>
        <button class="all-filter" type="button" data-filter="VPN">VPN</button>
        <button class="all-filter" type="button" data-filter="Education">Education</button>
        <button class="all-filter" type="button" data-filter="Discord">Discord</button>
        <button class="all-filter" type="button" data-filter="Telegram">Telegram</button>
        <button class="all-filter" type="button" data-filter="Other Subscriptions">Other Subscriptions</button>
    </div>

    <section class="row" aria-label="All subscription plans">
        <?php foreach ($products as $index => $product) : ?>
            <div class="col-md-3 mt-4 all-card" data-product="<?= html_escape($product[0]) ?>" data-aos="fade-right" data-aos-duration="800" data-aos-delay="<?= ($index % 8) * 40 ?>">
                <div class="card card-game subscription-product" style="width:16rem;">
                    <div class="card-img-top all-art">
                        <span class="all-badge">SUBSCRIPTION</span>
                        <img class="all-image" src="<?= base_url($product[3]) ?>" alt="<?= html_escape($product[0]) ?>">
                        <span class="all-country"><?= html_escape($product[1]) ?></span>
                    </div>
                    <div class="card-body">
                        <h3 class="all-name"><?= html_escape($product[0]) ?></h3>
                        <p class="text-muted small mb-0">Price</p>
                        <p class="all-price">NPR <?= number_format($product[2]) ?></p>
                        <button class="btn btn-primary btn-block btn-games font-weight-bold all-buy subscription-buy" type="button" hidden>Buy Now &rarr;</button>
                        <button class="btn btn-outline-primary btn-block font-weight-bold subscription-add" type="button" hidden data-product="<?= html_escape($product[0]) ?>" data-package="All Subscriptions" data-price="NPR <?= number_format($product[2]) ?>" data-checkout-page="<?= base_url('subscriptions/checkout') ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <p class="all-empty" id="all-empty">No subscriptions found.</p>
    </section>
</main>

<script>
    var allSearchInput = document.getElementById('all-search-input');
    var allCards = document.querySelectorAll('.all-card');
    var allFilters = document.querySelectorAll('.all-filter');
    var allEmpty = document.getElementById('all-empty');
    var activeFilter = '';

    function filterAllSubscriptions() {
        var searchTerm = allSearchInput.value.toLowerCase().trim();
        var visibleCount = 0;

        allCards.forEach(function (card) {
            var productName = card.dataset.product.toLowerCase();
            var matchesSearch = productName.indexOf(searchTerm) !== -1;
            var matchesFilter = !activeFilter || productName.indexOf(activeFilter.toLowerCase()) !== -1;
            var visible = matchesSearch && matchesFilter;
            card.style.display = visible ? '' : 'none';
            if (visible) { visibleCount += 1; }
        });

        allEmpty.style.display = visibleCount ? 'none' : 'block';
    }

    allSearchInput.addEventListener('input', filterAllSubscriptions);
    allFilters.forEach(function (filter) {
        filter.addEventListener('click', function () {
            activeFilter = filter.dataset.filter;
            allFilters.forEach(function (item) { item.classList.remove('active'); });
            filter.classList.add('active');
            filterAllSubscriptions();
        });
    });

</script>
<script src="<?= base_url('assets/js/subscription-cart.js') ?>"></script>