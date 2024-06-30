<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error <?php echo $errorCode; ?></title>
    <link rel="stylesheet" href="/assets/css/error.css">
</head>
<body>
    <header>
        <h1>
            <img src="/assets/img/favicon_io/favicon-32x32.png" alt="Logo" width="30" height="30">
            Oops!!, we have some problems here...
        </h1>
    </header>
    <main>
        <h2>Error <?php echo $errorCode; ?></h2>
        <p><?php echo htmlspecialchars($message); ?></p>
        <button class="cta-button" onclick="window.location.href='/'">Go to Homepage</button>
    </main>
    <footer>
        <p>&copy; 2024 Blogycopia. All rights reserved.</p>
    </footer>
</body>
</html>
