<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="/assets/img/favicon_io/favicon.ico">
    <link rel="stylesheet" href="/assets/css/register.css">
    <script src="/assets/js/register.js"></script>

    <title>Register</title>
</head>

<body>
    <div class="register-container">
        <h2>Register</h2>
        <form class="normal-register" action="/authentication/register" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <input type="submit" value="Register">
        </form>
        
        <button class="toggle-button" onclick="toggleSocialRegister()">Register with Social Account</button>
        
        <a class="login-link" href="/authentication/login">Already have an account? Login</a>
        
        <div class="social-register slide-out">
            <button class="google-register">Register with Google</button>
            <button class="facebook-register">Register with Facebook</button>
            <button class="apple-register">Register with Apple</button>
            <button class="toggle-button" onclick="toggleSocialRegister()">Back to Register</button>
        </div>
    </div>
</body>