<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify your email | StreamNest</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>css/styling.css">
</head>
<body class="verification-page">
    <main class="verification-card text-center">
        <div class="verification-icon" aria-hidden="true"><span>✓</span></div>
        <p class="verification-kicker">ONE LAST STEP</p>
        <h1>Verify your email</h1>
        <p class="verification-copy">We sent a verification link to <strong><?=htmlspecialchars($email, ENT_QUOTES, 'UTF-8');?></strong>.</p>
        <p class="verification-copy">Please check your inbox and click <strong>Verify my account</strong>. Hold on while we get your login ready.</p>
        <div class="verification-loader" role="status" aria-label="Waiting for email verification"><span></span><span></span><span></span></div>
        <p class="verification-note">This page will redirect you to login automatically after verification.</p>
    </main>
    <script>
        const statusUrl = <?=json_encode(base_url('welcome/verification_status?email=' . rawurlencode($email)));?>;
        const loginUrl = <?=json_encode(base_url('welcome/login'));?>;

        window.setInterval(function () {
            fetch(statusUrl, { headers: { 'Accept': 'application/json' } })
                .then(function (response) { return response.json(); })
                .then(function (status) {
                    if (status.verified) {
                        window.location.href = loginUrl;
                    }
                });
        }, 2000);
    </script>
</body>
</html>
