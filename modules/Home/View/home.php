<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon_io/favicon.ico">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Blogycopia</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            width: 100%;
            background-color: #ffffff;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
            display: flex;
            align-items: center;
        }

        header h1 img {
            margin-right: 10px; 
            margin-left: 10px;
            padding: 5px;
        }

        nav a {
            margin: 0 15px;
            color: #666;
            text-decoration: none;
            font-size: 16px;
        }

        nav a:hover {
            color: #000;
        }

        .main-content {
            padding: 40px;
            text-align: center;
        }

        .main-content h2 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .main-content h3 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .main-content p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .cta-button {
            background-color: #000;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }

        .cta-button:hover {
            background-color: #333;
        }

        .features {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            width: 100%;
            max-width: 1200px;
        }

        .feature {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 30%;
            text-align: center;
        }

        .feature h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .feature p {
            font-size: 16px;
            color: #666;
        }

        footer {
            margin-top: auto;
            padding: 20px;
            background-color: #ffffff;
            width: 100%;
            text-align: center;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        footer p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <header>
        <h1>
            <img src="/assets/img/favicon_io/favicon-32x32.png" alt="Logo" width="30" height="30">
            Blogycopia
        </h1>

        // Make it as a post to logout.
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