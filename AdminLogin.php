<?php
include "config.php";

if(isset($_POST['login'])){

$username = $_POST['username'];
$password = $_POST['password'];
$role = "admin"; // Fixed role

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

if(mysqli_num_rows($query) > 0){

    $user = mysqli_fetch_assoc($query);

    if(password_verify($password, $user['password'])){

        // Generate unique session token
        $token = bin2hex(random_bytes(32));

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['session_token'] = $token;

        // Save token in database
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

        header("Location: AddVideo.php");
        exit();

    } else {
        $error = "Invalid username or password.";
    }

} else {
    $error = "Invalid username or password.";
}
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>SIB Paguokon Rebana Dance Library - Administrator Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=11">
</head>

<body>

<div class="login-container">

    <h2 class="login-title">
        SIB Paguokon Rebana Dance Library System
    </h2>

    <div class="login-box">

        <img src="images/login-card-background.png" class="login-card-bg">

        <p class="login-note">
            This login page is for administrators only.
        </p>

        <form method="POST">

            <input type="text"
                   name="username"
                   placeholder="Username"
                   required>

            <input type="password"
                   name="password"
                   placeholder="Password"
                   required>

            <button name="login">Admin Login</button>

            <div class="auth-switch">
                Member?
                <a href="Login.php">Member Login</a>
            </div>

            <a href="index.php" class="home-btn">
                Back to Home
            </a>

        </form>

        <?php
        if(isset($error)){
            echo "<div class='error'>$error</div>";
        }
        ?>

    </div>


</div>

</body>
</html>