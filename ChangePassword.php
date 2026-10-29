<?php
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: Login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$token   = $_SESSION['session_token'];

/* Get current user */
$stmt = mysqli_prepare($conn,
"SELECT password, session_token FROM users WHERE id=?");

mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

/* Check session token */
if(!$user || $user['session_token'] != $token){
    session_destroy();
    header("Location: Login.php");
    exit();
}

if(isset($_POST['change'])){

    $current = trim($_POST['current_password']);
    $new = trim($_POST['new_password']);
    $confirm = trim($_POST['confirm_password']);

    if($current=="" || $new=="" || $confirm==""){
        $error="Please fill in all fields.";
    }

    elseif(!password_verify($current,$user['password'])){
        $error="Current password is incorrect.";
    }

    elseif($new != $confirm){
        $error="New passwords do not match.";
    }

    elseif(strlen($new) < 6){
        $error="Password must be at least 6 characters.";
    }

    elseif(password_verify($new,$user['password'])){
        $error="New password cannot be the same as the current password.";
    }

    else{

        $hash=password_hash($new,PASSWORD_DEFAULT);

        $update=mysqli_prepare($conn,
        "UPDATE users SET password=? WHERE id=?");

        mysqli_stmt_bind_param($update,"si",$hash,$user_id);

        if(mysqli_stmt_execute($update)){
            $success="Password changed successfully.";
        }else{
            $error="Unable to change password.";
        }

    }

}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Change Password</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="login-container">

<div class="form-card">

<h2>Change Password</h2>

<?php
if(isset($success)){
    echo "<div class='success-box'>$success</div>";
}

if(isset($error)){
    echo "<div class='profile-error'>$error</div>";
}
?>

<form method="POST">

<input
type="password"
name="current_password"
placeholder="Current Password"
required>

<input
type="password"
name="new_password"
placeholder="New Password"
required>

<input
type="password"
name="confirm_password"
placeholder="Confirm New Password"
required>

<button type="submit" name="change">
Change Password
</button>

<a href="Profile.php" class="home-btn">
← Back to Profile
</a>

</form>

</div>

</div>

</body>

</html>