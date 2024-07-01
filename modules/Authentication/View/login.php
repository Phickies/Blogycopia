<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="/assets/img/favicon_io/favicon.ico">
    <link rel="stylesheet" href="/assets/css/login.css">
    <script src="/assets/js/login.js"></script>

    <title>Login</title>

<body>
    <div class="login-container">

        <h2>Login</h2>

        <form class="normal-login" action="/authentication/login" method="POST">
            <input type="text" name="username" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <p><?=$error?></p>
            <input type="submit" value="Login">
        </form>

        <button class="toggle-button" onclick="toggleSocialLogin()">Login with Social Account</button>
        
        <a class="register-link" href="/authentication/register">Don't have an account? Register</a>
        <a class="forgot-password" href="/authentication/login/forgot-password">Forgot Password?</a>

        <div class="social-login slide-out">
            <button class="google-login">Login with Google</button>
            <button class="facebook-login">Login with Facebook</button>
            <button class="apple-login">Login with Apple</button>
            <button class="toggle-button" onclick="toggleSocialLogin()">Back to Login</button>
        </div>
</body>

</html>