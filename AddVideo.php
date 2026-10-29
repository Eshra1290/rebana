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

if($_SESSION['role']!="admin"){
header("Location: Login.php");
exit();
}

if(isset($_POST['submit'])){

$step=$_POST['step_name'];
$category=$_POST['category'];
$link=$_POST['youtube_link'];
$desc=$_POST['description'];

/* Auto-convert YouTube link to embed link */
if(strpos($link, "youtube.com/shorts/") !== false){
    $parts = explode("shorts/", $link);
    $video_id = explode("?", $parts[1])[0];
    $link = "https://www.youtube.com/embed/" . $video_id;
}
else if(strpos($link, "watch?v=") !== false){
    $parts = explode("watch?v=", $link);
    $video_id = explode("&", $parts[1])[0];
    $link = "https://www.youtube.com/embed/" . $video_id;
}
else if(strpos($link, "youtu.be/") !== false){
    $parts = explode("youtu.be/", $link);
    $video_id = explode("?", $parts[1])[0];
    $link = "https://www.youtube.com/embed/" . $video_id;
}

mysqli_query($conn,"INSERT INTO videos
(step_name,category,youtube_link,description)
VALUES ('$step','$category','$link','$desc')");

$message="Video added successfully!";
}
?>

<html>

<head>
    <link rel="stylesheet" href="style.css?v=4">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="add-video-page">

<?php include "NavigationAdmin.php"; ?>

<div class="content add-video-content">

<div class="form-card">

<h2>Add New Dance Pattern</h2>

<?php if(isset($message)) echo "<p class='success'>$message</p>"; ?>

<form method="POST">

<input type="text" name="step_name" placeholder="Step name" required>

<select name="category">
<option value="Praise">Praise</option>
<option value="Worship">Worship</option>
<option value="Praise Songs">Praise Songs</option>
<option value="Worship Songs">Worship Songs</option>
</select>

<input type="text" name="youtube_link" placeholder="Paste YouTube link" required>

<textarea name="description" placeholder="Description"></textarea>

<button name="submit">Add Video</button>

</form>

</div>

</div>

</body>
</html>