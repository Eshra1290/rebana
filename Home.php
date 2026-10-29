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

if(!isset($_SESSION['user'])){
    header("Location: Login.php");
}

if($_SESSION['role'] != "dancer"){
    header("Location: Logout.php");
    exit();
}

?>

<html>
<head>
<link rel="stylesheet" href="style.css?v=4">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<?php include "Navigation.php"; ?>

<div class="content">

<h1 class="page-title">Welcome to SIB Paguokon Rebana Dance Library</h1>

<div class="card">

<h2>Welcome, <?php echo $_SESSION['user']; ?>! 👋</h2>

<p>
Welcome to the SIB Paguokon Rebana Dance Library. This platform is designed to help dancers
learn and practice rebana dance patterns through organized video tutorials.
</p>

<p>
Please use the navigation menu to explore the available dance categories:
</p>

<ul>
    <li>Praise Steps</li>
    <li>Worship Steps</li>
    <li>Praise with Songs</li>
    <li>Worship with Songs</li>
</ul>

<p>
Enjoy learning!
</p>

</div>

</div>


</body>
</html>