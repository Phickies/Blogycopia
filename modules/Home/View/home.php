<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" type="image/x-icon" href="assets/img/favicon_io/favicon.ico">
    <link rel="stylesheet" href="/assets/css/home.css">
    
    <title>Blogycopia</title>
</head>

<body>
    <header>
        <h1>
            <img src="/assets/img/favicon_io/favicon-32x32.png" alt="Logo" width="30" height="30">
            Blogycopia
        </h1>

        <nav>
            <a href="/authentication/logout">Logout</a>
        </nav>
    </header>
    <div class="main-content">
        <h2><?= $heading ?>, <?= $username ?>!</h2>
        <h3>We are glad to have you here.</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vehicula felis nec risus tempor, a dictum metus tincidunt.</p>
        <button class="cta-button" onclick="window.location.href='/get-started'">Get Started</button>
    </div>
    <div class="features">
        <div class="feature">
            <h3>Feature One</h3>
            <p>Detailed description of the first feature of the website.</p>
        </div>
        <div class="feature">
            <h3>Feature Two</h3>
            <p>Detailed description of the second feature of the website.</p>
        </div>
        <div class="feature">
            <h3>Feature Three</h3>
            <p>Detailed description of the third feature of the website.</p>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Blogycopia. All rights reserved.</p>
    </footer>
</body>

</html>