<h1>Registartion for your new account</h1>

<form action="login.php?action=register" method="post">

    <label for="username">Username:<br></label>
    <input type="text" name="username" required><br>

    <label for="email">Email:<br></label>
    <input type="email" name="email" required><br>

    <label for="password">Password:<br></label>
    <input type="password" name="password" required><br>

    <label for="re_password">Retype password:<br></label>
    <input type="password" name="re_password" required><br>

    <input type="submit" name="submit" value="register">

    <a href="login">Back to login page</a>
</form>