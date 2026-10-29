<?php
//include "config.php";

if($_SESSION['role'] == "admin" || $_SESSION['role'] == "dancer"){
} else {
    header("Location: Login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$token = $_SESSION['session_token'];

$stmt = mysqli_prepare($conn,
    "SELECT id, username, phone, role, session_token FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user || $user['session_token'] != $token) {
    session_destroy();
    header("Location: Login.php");
    exit();
}

if (isset($_POST['save'])) {

    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);

    if ($username == "" || $phone == "") {
        $error = "All fields are required.";
    } else {

        $check = mysqli_prepare($conn,
            "SELECT id FROM users WHERE phone = ? AND id != ?");
        mysqli_stmt_bind_param($check, "si", $phone, $user_id);
        mysqli_stmt_execute($check);
        $checkResult = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($checkResult) > 0) {
            $error = "Phone number already exists.";
        } else {

            $update = mysqli_prepare($conn,
                "UPDATE users SET username = ?, phone = ? WHERE id = ?");
            mysqli_stmt_bind_param($update, "ssi", $username, $phone, $user_id);
            mysqli_stmt_execute($update);

            $_SESSION['user'] = $username;

            $success = "Profile updated successfully!";

            // refresh data
            $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
        }
    }
}
?>

<html>

<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css?v=3">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Profile</title>

</head>

<body>

<?php
if($_SESSION['role'] == "admin"){
    include "NavigationAdmin.php";
}else{
    include "Navigation.php";
}
?>

<div class="profile-wrapper">

<div class="profile-card">

<div class="profile-icon">👤</div>

<h2>My Profile</h2>

<?php if(isset($success)) echo "<div class='profile-success'>$success</div>"; ?>
<?php if(isset($error)) echo "<div class='profile-error'>$error</div>"; ?>

<form method="POST">

<input type="text" name="username"
value="<?php echo htmlspecialchars($user['username']); ?>"
required>

<input type="text" name="phone"
value="<?php echo htmlspecialchars($user['phone']); ?>"
required>

<div class="role-box">
Role: <?php echo ucfirst($user['role']); ?>
</div>

<button class="save-btn" name="save">
Save Changes
</button>

<a href="ChangePassword.php" class="password-btn">
Change Password
</a>

</form>

</div>

</div>

</body>
</html>