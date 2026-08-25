<!-- jumbotron -->

<div class="jumbotron jumbotron-fluid index-jumbotron rellax" data-rellax-speed="-2">
    <div class="container">
        <h1 class="display-2 mt-5 header-jumbotron">Welcome to Stream Nest</h1>
        <p class="lead text-jumbotron">
            <strong>Your Trusted Digital &amp; Entertainment Store</strong><br>
            Shop premium subscriptions, gift cards, digital products, games, and electronics &mdash; all in one place.
        </p>
        <a class="btn btn-danger btn-lg hero-action" href="<?= base_url('store') ?>">KEEP SHOPPING &rarr;</a>
    </div>
</div>
<!-- end jumbotron -->


<!-- container -->
<div class="container">
    <!-- info panel -->
    <div class="row justify-content-center">
        <div class="col-10 info-panel">
            <div class="row">
                <div class="col-md">
                    <img src="<?= base_url('assets/'); ?>img/slot-machine.png" alt="" srcset="" class="float-left">
                    <h4>Fun!</h4>
                    <p>Enjoy premium subscriptions at great prices!</p>
                </div>
                <div class="col-md">
                    <img src="<?= base_url('assets/'); ?>img/gamepad.png" alt="" srcset="" class="float-left">
                    <h4>Relax!</h4>
                    <p>Easy, secure, and hassle-free shopping!</p>
                </div>
                <div class="col-md">
                    <img src="<?= base_url('assets/'); ?>img/nintendo-switch.png" alt="" srcset="" class="float-left">
                    <h4>Premium!</h4>
                    <p>Get your favorite premium subscriptions &mdash; all in one place!</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end info panel -->

<!-- title -->
<div class="row topup">
    <div class="col-md-10 container mt-5">
        <h2 class="title text-center" data-aos="zoom-out" data-aos-duration="1200">Game Top-Up Vouchers Made Easy!</h2>
    </div>
    <br>
    <!-- title -->
    <div class="owl-carousel mt-5 container owl-theme">
        <a class="topup-card-link" href="<?= base_url('topup/mobile_legends') ?>">
            <div class="b-game-card item" data-aos="fade-down" data-aos-duration="1200">
                <div class="b-game-card__cover" style="background-image: url('<?= base_url('assets/img/mobile-legends.png') ?>');"></div>
            </div>
        </a>
        <a class="topup-card-link" href="<?= base_url('topup/free_fire') ?>">
            <div class="b-game-card item" data-aos="fade-up" data-aos-duration="1300">
                <div class="b-game-card__cover" style="background-image: url('<?= base_url('assets/img/free-fire.png') ?>');"></div>
            </div>
        </a>
        <a class="topup-card-link" href="<?= base_url('topup/pubg') ?>">
            <div class="b-game-card item" data-aos="fade-up" data-aos-duration="1500">
                <div class="b-game-card__cover" style="background-image: url('<?= base_url('assets/img/pubg.png') ?>');"></div>
            </div>
        </a>
        <br>
        <br>
    </div>
</div>

<section class="popular-subscriptions container mt-5">
    <h2 class="title text-center" data-aos="zoom-out">Popular Subscriptions</h2>
    <div class="row">
        <div class="col-md-3 mb-4" data-aos="fade-up">
            <article class="game-tile">
                <img src="<?= base_url('media/netflix.jpg') ?>" alt="Netflix">
                <h3>Netflix</h3>
                <p>Enjoy premium entertainment on your favorite screens.</p>
                <a href="<?= base_url('subscriptions/netflix') ?>">View Plans &rarr;</a>
            </article>
        </div>
        <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="80">
            <article class="game-tile">
                <img src="<?= base_url('media/spotify.jpg') ?>" alt="Spotify">
                <h3>Spotify</h3>
                <p>Listen to your favorite music without limits.</p>
                <a href="<?= base_url('subscriptions/spotify') ?>">View Plans &rarr;</a>
            </article>
        </div>
        <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="160">
            <article class="game-tile">
                <img src="<?= base_url('media/youtube.jpg') ?>" alt="YouTube Premium">
                <h3>YouTube Premium</h3>
                <p>Watch and listen with a premium experience.</p>
                <a href="<?= base_url('subscriptions/youtube') ?>">View Plans &rarr;</a>
            </article>
        </div>
        <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="240">
            <article class="game-tile">
                <img src="<?= base_url('media/discord.avif') ?>" alt="Discord">
                <h3>Discord</h3>
                <p>Upgrade your community with Nitro and boosts.</p>
                <a href="<?= base_url('subscriptions/discord') ?>">View Plans &rarr;</a>
            </article>
        </div>
    </div>
</section>

<div class="container">
    <div class="row mt-3">
        <div class="col-md-6" data-aos-duration="1500" data-aos="fade-right">
            <img src="<?= base_url('assets/') ?>img/hayabusa.png" class="img-fluid" alt="" srcset="">
        </div>
        <div class="col-md-6 my-auto" data-aos-duration="1500" data-aos="fade-left">
            <h1 class="text-center title mt-5">Interested in joining us?</h1>
            <p class="text-center text-section">Let us support Indonesian products together! As millennials, we should
                always support programs and games made by Indonesian creators. Join us to support their work and turn
                your hobby into progress for the nation, starting today!</p>
            <a href="<?= base_url('welcome/registration') ?>"><button class="btn btn-section btn-block">Join us today!</button></a>
        </div>
    </div>
</div>

<div class="container">
    <div class="end-section row mt-5" data-aos-duration="1200" data-aos="zoom-out">
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="font-weight-bold">Punya game? yuk promosikan dan publish sekarang!</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <a href="<?= base_url('welcome/publisher') ?>">
                        <h5 class="btn btn-light font-weight-bold">Become a Publisher</h5>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content login-modal-content">
            <div class="modal-header">
                <h2 class="modal-title font-weight-bold" id="exampleModalCenterTitle">Log in to StreamNest</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body login-modal-body">
                <div class="row align-items-center">
                        <div class="col-md-6 login-visual">
                            <span class="login-kicker">WELCOME BACK</span>
                            <img src="<?= base_url('assets/'); ?>img/_____2x-removebg-preview.png" class="img-fluid" alt="StreamNest illustration">
                            <p>Pick up where you left off.</p>
                        </div>
                        <div class="col-md-6 login-form-panel">
                            <form action="<?= base_url('welcome/index') ?>" method="post">
                                <div class="form-group">
                                    <label class="label-font" for="exampleFormControlInput1">
                                        Email</label>
                                    <input type="text" value="<?= set_value('email'); ?>" class="form-control" name="email" id="email" placeholder="Enter your email">
                                    <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                <div class="form-group">
                                    <label class="label-font" for="exampleFormControlInput1">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password">
                                    <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1">
                                        Remember me
                                    </label>
                                </div>
                                <p class="terms">By logging in, you agree to our <i>privacy policy and legal terms</i>.
                                </p>
                                <button type="submit" class="btn btn-modal btn-block">Log In</button>
                                <div class="text-center my-3">or</div>
                                <a class="btn btn-outline-dark btn-block" href="#" role="button" aria-label="Login with Google">
                                    <i class="fab fa-google mr-2"></i> Login with Google
                                </a>
                                <p class="text-center mt-3 mb-0">Don't have an account?
                                    <a href="<?= base_url('welcome/registration') ?>">Register</a>
                                </p>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/rellax/1.12.1/rellax.min.js"></script>

<script>
    var rellax = new Rellax('.rellax');

    window.addEventListener('load', function () {
        if (new URLSearchParams(window.location.search).get('login') === '1' && window.jQuery) {
            $('#exampleModalCenter').modal('show');
        }
    });
</script>