<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="/assets/img/favicon_io/favicon.ico">
    <link rel="stylesheet" href="/assets/css/code-type-in.css">
    <script src="/assets/js/code-type-in.js"></script>

    <title>Enter Authentication Code</title>
</head>

<body>
    <div class="auth-code-container">
        <h2>Enter Code</h2>
        <form action="/verify-code" method="POST" id="authCodeForm">
            <div class="code-inputs">
                <input type="text" name="code1" maxlength="1" required>
                <input type="text" name="code2" maxlength="1" required>
                <input type="text" name="code3" maxlength="1" required>
                <input type="text" name="code4" maxlength="1" required>
                <input type="text" name="code5" maxlength="1" required>
                <input type="text" name="code6" maxlength="1" required>
            </div>
            <p class="error-message"><?= $error ?></p>
            <input type="submit" value="Verify Code">
        </form>
        <button class="toggle-button" onclick="resetCode()">Reset Code</button>
        <a href="/">Cancel</a>
    </div>
</body>

</html>