<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Admin Login</title>
    
</head>
<body>

    <div class="header">
    <img src="../Resources/Images/Logo.png" class="logo-black"alt="Black Logo">
</div>

    <!--Container for the login box-->
    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="adminLogin.php" method="post">
            <input class="login-input" type="text" name="username" placeholder="Username" required>
            <input class="login-input" type="password" name="password" placeholder="Password" required>
            <button class="login-button" type="submit" name="adminLogin">Login</button>
        </form>
    </div>
</body>
</html>