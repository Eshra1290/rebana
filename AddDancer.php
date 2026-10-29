<?php
include "config.php";

if(!isset($_SESSION['user_id']) || !isset($_SESSION['user']) || !isset($_SESSION['session_token'])){
    header("Location: Login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$token = $_SESSION['session_token'];

$check = mysqli_query($conn, "SELECT session_token FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($check);

if($user['session_token'] != $token){
    session_destroy();
    header("Location: Login.php");
    exit();
}

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: Login.php");
    exit();
}

/* DELETE USER */
if(isset($_GET['delete'])){

    $id = (int)$_GET['delete'];

    if($id == $_SESSION['user_id']){
        die("You cannot delete your own account.");
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: AddDancer.php");
    exit();
}

/* ADD USER */
if(isset($_POST['submit'])){

    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = mysqli_prepare($conn,
    "SELECT * FROM users WHERE phone = ? AND role = ?");

	mysqli_stmt_bind_param($stmt, "ss", $phone, $role);

	mysqli_stmt_execute($stmt);

	$check = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($check) > 0){
        $error = "This phone number already exists for this role.";
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

        $message = "User added successfully!";
    }
}

/* SEARCH USER */
$search = "";

if(isset($_GET['search'])){
    $search = $_GET['search'];

    $users = mysqli_query($conn, "SELECT * FROM users
        WHERE username LIKE '%$search%'
        OR phone LIKE '%$search%'
        ORDER BY role, username");
}else{
    $users = mysqli_query($conn, "SELECT * FROM users
        ORDER BY role, username");
}
?>

<html>

<head>
<link rel="stylesheet" href="style.css?v=4">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Dancer</title>
</head>

<body>

<?php include "NavigationAdmin.php"; ?>

<div class="add-video-content user-manage-page">

    <div class="form-card">

        <h2>Add New User</h2>

        <?php if(isset($message)) echo "<p class='success'>$message</p>"; ?>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">

            <input type="text" name="username" placeholder="Name" required>

            <input type="text" name="phone" placeholder="Phone number" required>

            <select name="role" required>
                <option value="dancer">Dancer</option>
                <option value="admin">Admin</option>
            </select>

            <input type="password" name="password" placeholder="Password" required>

            <button name="submit">Add User</button>

        </form>

    </div>

    <div class="form-card">

        <h2>Manage Users</h2>

        <form method="GET">

            <input type="text"
                   name="search"
                   placeholder="Search name or phone number"
                   value="<?php echo $search; ?>">

            <button type="submit">Search</button>

        </form>

        <br>

        <div class="users-grid">

        <?php while($row = mysqli_fetch_assoc($users)){ ?>

            <div class="user-row">

                <div>
                    <strong>Username:</strong> 
                    <span class="user-value"><?php echo $row['username']; ?></span><br>

                    <strong>Phone:</strong> 
                    <span class="user-value"><?php echo $row['phone']; ?></span><br>

                    <small><strong>Role:</strong> <?php echo ucfirst($row['role']); ?></small>
                </div>

                <a href="AddDancer.php?delete=<?php echo $row['id']; ?>"
                   onclick="return confirm('Are you sure you want to delete this user?')">
                    <button type="button" class="delete-user-btn">Delete</button>
                </a>

            </div>

        <?php } ?>

        </div>

    </div>

</div>

</body>
</html>