<?php
include "config.php";

if(isset($_POST['login'])){

$username=$_POST['username'];
$password=$_POST['password'];
$role = "dancer";

$stmt = mysqli_prepare($conn,
    "SELECT * FROM users
     WHERE username = ?
     AND role = ?");

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $username,
    $role
);

mysqli_stmt_execute($stmt);

$query = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($query)>0){

$user=mysqli_fetch_assoc($query);

    if(password_verify($password, $user['password'])){

// generate unique session token
$token = bin2hex(random_bytes(32));

$_SESSION['user_id'] = $user['id'];
$_SESSION['user'] = $user['username'];
$_SESSION['role'] = $user['role'];
$_SESSION['session_token'] = $token;

// save token in database
$user_id = $user['id'];

$stmt = mysqli_prepare($conn,
    "UPDATE users
     SET session_token = ?
     WHERE id = ?");

mysqli_stmt_bind_param(
    $stmt,
    "si",
    $token,
    $user_id
);

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: Home.php");
exit();

}else{
$error="Invalid login credentials or role";
}

}else{
        $error="Invalid login credentials or role";
    }

}
?>

<html>

<head>
<title>SIB Paguokon Rebana Dance Library - Member Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css?v=4">


</head>

<body>

<div class="login-container">
    <h2 class="login-title">SIB Paguokon Rebana Dance Library System</h2> <!-- title outside card -->

<div class="login-box">
    <!-- Background image as <img> -->
    <img src="images/login-card-background.png" class="login-card-bg">
    
    <p class="login-note">
    This login is only for registered SIB Paguokon Rebana Dance Library members.
	</p>

<form method="POST">

<input type="text" name="username" placeholder="Username" required>

<input type="password" name="password" placeholder="Password" required>

<button name="login">Login</button>

<div class="auth-switch">
    New dancer?
    <a href="Signup.php">Create account</a>
</div>
    
<div class="auth-switch">
    Administrator?
    <a href="AdminLogin.php">Admin Login</a>
</div>
    
<a href="index.php" class="home-btn">
    Back to Home
</a>

</form>

<?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>

</div>


</div>
</body>
</html>