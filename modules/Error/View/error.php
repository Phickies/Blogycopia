<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error <?php echo $errorCode; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <header>
        <h1>Error <?php echo $errorCode; ?></h1>
    </header>
    <main>
        <p><?php echo htmlspecialchars($message); ?></p>
    </main>
    <footer>
        <p>&copy; 2024 Blogycopia</p>
    </footer>
</body>

</html>