<?php
//include "config.php";

if(isset($_POST['signup'])){

    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = "dancer";

    $stmt = mysqli_prepare($conn,
    "SELECT * FROM users WHERE phone = ? AND role = ?");

        $role = "dancer";

        mysqli_stmt_bind_param($stmt, "ss", $phone, $role);

        mysqli_stmt_execute($stmt);

        $check = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($check) > 0){
        $error = "This phone number is already registered as a dancer.";
    }else{

        $stmt = mysqli_prepare($conn,
            "INSERT INTO users
            (username, phone, password, role)
            VALUES (?, ?, ?, ?)");

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $username,
            $phone,
            $password,
            $role
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $message = "Account created successfully! Redirecting to login...";
header("refresh:2;url=Login.php"); // redirect after 2 seconds
    }
}
?>

<html>

<head>
<title>Dancer Signup</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css?v=4">
</head>

<body>

<div class="login-container">

<h2 class="login-title">Dancer Signup</h2>

<div class="login-box">

<img src="images/login-card-background.png" class="login-card-bg">

<form method="POST">

<input type="text" name="username" placeholder="Full name" required>

<input type="text" name="phone" placeholder="Phone number" required>

<input type="password" name="password" placeholder="Create password" required>

<button name="signup">Sign Up</button>

<div class="auth-switch">
    Already have an account?
    <a href="Login.php">Login</a>
</div>
    
    <a href="index.php" class="home-btn">
    Back to Home
</a>

</form>

<?php if(isset($message)) echo "<div class='success-box'>$message</div>"; ?>
<?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>

</div>


</div>

</body>
</html>