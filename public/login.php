<?php include('../includes/header.php'); ?>
<link rel="stylesheet" href="css/login.css">
<div class="login-container">
    <h1>Login</h1>
    <form action="login.php" method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        
        <div class="remember-me">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Remember Me</label>
        </div>
        
        <button type="submit">Login</button>
        
        <div class="links">
            <a href="reset-password.php">Forgot Password?</a>
        </div>
    </form>
</div>
<?php include('../includes/footer.php'); ?>