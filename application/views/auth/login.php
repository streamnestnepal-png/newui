<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in | StreamNest</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>css/styling.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="login-page">
    <main class="login-page-card">
        <section class="login-page-visual">
            <span>WELCOME BACK</span>
            <img src="<?=base_url('assets/');?>img/_____2x-removebg-preview.png" alt="StreamNest illustration">
            <h1>Play more. Worry less.</h1>
            <p>Your digital entertainment is waiting.</p>
        </section>
        <section class="login-page-form">
            <a class="login-back-link" href="<?=base_url('welcome');?>"><i class="fas fa-arrow-left"></i> Back to home</a>
            <p class="login-kicker">ACCOUNT LOGIN</p>
            <h2>Log in to StreamNest</h2>
            <?php if ($this->session->flashdata('success-verify')) : ?>
                <div class="alert alert-success" role="alert">Your email has been verified successfully. Please log in to continue.</div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('purchase_notice')) : ?>
                <div class="alert alert-info" role="alert"><?= html_escape($this->session->flashdata('purchase_notice')) ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('google-login-error')) : ?>
                <div class="alert alert-danger" role="alert"><?= html_escape($this->session->flashdata('google-login-error')) ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('google-signup-success')) : ?>
                <div class="alert alert-success" role="alert"><?= html_escape($this->session->flashdata('google-signup-success')) ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('fail-login') || $this->session->flashdata('fail-pass')) : ?>
                <div class="alert alert-danger" role="alert">Incorrect email or password. Please try again.</div>
            <?php endif; ?>
            <p class="login-intro">Access your account and continue where you left off.</p>
            <form action="<?=base_url('welcome/index');?>" method="post">
                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input id="login-email" type="email" name="email" class="form-control" placeholder="Enter your email" value="<?=set_value('email');?>" required>
                    <?=form_error('email', '<small class="text-danger">', '</small>');?>
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input id="login-password" type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    <?=form_error('password', '<small class="text-danger">', '</small>');?>
                </div>
                <button type="submit" class="btn btn-modal btn-block">Log In</button>
                <div class="login-divider"><span>or</span></div>
                <?php $this->config->load('google'); ?>
                <?php if ($this->config->item('google_client_id')) : ?>
                    <div id="google-login-button" class="d-flex justify-content-center"></div>
                <?php endif; ?>
            </form>
            <p class="login-register-text">Don't have an account? <a href="<?=base_url('welcome/registration');?>">Register now</a></p>
        </section>
    </main>
</body>
<?php if ($this->config->item('google_client_id')) : ?>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
    window.addEventListener('load', function () {
        google.accounts.id.initialize({
            client_id: <?= json_encode($this->config->item('google_client_id')); ?>,
            ux_mode: 'popup',
            callback: function (response) {
                fetch(<?= json_encode(base_url('welcome/google_login')); ?>, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({ credential: response.credential, flow: 'login' })
                })
                    .then(function (result) { return result.json(); })
                    .then(function (result) {
                        if (result.success) {
                            window.location.href = result.redirect;
                        } else {
                            window.alert(result.message || 'Google login failed.');
                        }
                    })
                    .catch(function () { window.alert('Google login failed. Please try again.'); });
            }
        });
        google.accounts.id.renderButton(document.getElementById('google-login-button'), {
            type: 'standard',
            theme: 'outline',
            size: 'large',
            text: 'signin_with',
            shape: 'rectangular',
            width: Math.min(360, document.querySelector('.login-page-form').clientWidth - 4)
        });
    });
</script>
<?php endif; ?>
</html>
