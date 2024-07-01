<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon_io/favicon.ico">
    <link rel="stylesheet" href="/assets/css/forgot-password.css">
    
    <title>Forget Password</title>
</head>

<body>
    <div class="forget-password-container">
        <p>Please fill in your registered email</p>
        <form action="/authentication/login/forgot-password" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <p class="error-message"><?=$error?></p>
            <input type="submit" value="Send code to this email">
        </form>
        <a class="login-link" href="/authentication/login">Back to Login</a>
    </div>
</body>

</html>