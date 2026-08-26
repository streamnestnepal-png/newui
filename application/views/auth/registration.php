<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | StreamNest</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>css/styling.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="login-page">
    <main class="login-page-card registration-page-card">
        <section class="login-page-visual">
            <span>JOIN STREAMNEST</span>
            <img src="<?=base_url('assets/');?>img/hayabusa.png" alt="StreamNest illustration">
            <h1>Make your move.</h1>
            <p>Start your next digital adventure today.</p>
        </section>
        <section class="login-page-form registration-page-form">
            <a class="login-back-link" href="<?=base_url('welcome');?>"><i class="fas fa-arrow-left"></i> Back to home</a>
            <p class="login-kicker">CREATE ACCOUNT</p>
            <h2>Register for StreamNest</h2>
            <?php if ($this->session->flashdata('purchase_notice')) : ?>
                <div class="alert alert-info" role="alert"><?= html_escape($this->session->flashdata('purchase_notice')) ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('email-fail')) : ?>
                <div class="alert alert-warning" role="alert"><?= html_escape($this->session->flashdata('email-fail')) ?></div>
            <?php endif; ?>
            <p class="login-intro">Create your account and unlock the full experience.</p>
            <form action="<?=base_url('welcome/registration');?>" method="post">
                <div class="form-group">
                    <label for="registration-name">Full name</label>
                    <input id="registration-name" type="text" name="nama" class="form-control" placeholder="Enter your full name" value="<?=set_value('nama');?>" required>
                    <?=form_error('nama', '<small class="text-danger">', '</small>');?>
                </div>
                <div class="form-group">
                    <label for="registration-email">Email</label>
                    <input id="registration-email" type="email" name="email" class="form-control" placeholder="Enter your email" value="<?=set_value('email');?>" required>
                    <?=form_error('email', '<small class="text-danger">', '</small>');?>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="registration-password">Password</label>
                        <input id="registration-password" type="password" name="password" class="form-control" placeholder="Create a password" required>
                        <?=form_error('password', '<small class="text-danger">', '</small>');?>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="registration-password-confirm">Confirm password</label>
                        <input id="registration-password-confirm" type="password" name="retype_password" class="form-control" placeholder="Repeat password" required>
                        <?=form_error('retype_password', '<small class="text-danger">', '</small>');?>
                    </div>
                </div>
                <div class="form-check registration-agreement">
                    <input class="form-check-input" type="checkbox" id="registration-agreement">
                    <label class="form-check-label" for="registration-agreement">I agree to the privacy policy and legal terms.</label>
                </div>
                <button type="submit" name="submit" id="btnsubmit" disabled class="btn btn-modal btn-block">Register Now!</button>
                <div class="login-divider"><span>or</span></div>
                <?php $this->config->load('google'); ?>
                <?php if ($this->config->item('google_client_id')) : ?>
                    <div id="google-signup-button" class="d-flex justify-content-center"></div>
                <?php endif; ?>
            </form>
            <p class="login-register-text">Already have an account? <a href="<?=base_url('welcome/login');?>">Log in</a></p>
        </section>
    </main>
    <script>
        const agreementCheckbox = document.getElementById('registration-agreement');
        const registerButton = document.getElementById('btnsubmit');
        agreementCheckbox.addEventListener('change', function () {
            registerButton.disabled = !this.checked;
        });
    </script>
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
                        body: new URLSearchParams({ credential: response.credential, flow: 'signup' })
                    })
                        .then(function (result) { return result.json(); })
                        .then(function (result) {
                            if (result.success) {
                                window.location.href = result.redirect;
                            } else {
                                window.alert(result.message || 'Google signup failed.');
                            }
                        })
                        .catch(function () { window.alert('Google signup failed. Please try again.'); });
                }
            });
            google.accounts.id.renderButton(document.getElementById('google-signup-button'), {
                type: 'standard',
                theme: 'outline',
                size: 'large',
                text: 'signup_with',
                shape: 'rectangular',
                width: Math.min(360, document.querySelector('.registration-page-form').clientWidth - 4)
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>
