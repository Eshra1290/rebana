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

// Pagination settings
$limit = 8;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$start = ($page - 1) * $limit;

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $query = "SELECT * FROM videos
              WHERE category='Worship'
              AND step_name LIKE '%$search%'
              LIMIT $start, $limit";

    $countQuery = "SELECT COUNT(*) AS total
                   FROM videos
                   WHERE category='Worship'
                   AND step_name LIKE '%$search%'";
} else {

    $query = "SELECT * FROM videos
              WHERE category='Worship'
              LIMIT $start, $limit";

    $countQuery = "SELECT COUNT(*) AS total
                   FROM videos
                   WHERE category='Worship'";
}

$result = mysqli_query($conn, $query);

$countResult = mysqli_query($conn, $countQuery);
$totalVideos = mysqli_fetch_assoc($countResult)['total'];

$totalPages = ceil($totalVideos / $limit);
?>

<html>

<head>
<link rel="stylesheet" href="style.css?v=4">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<?php include "Navigation.php"; ?>

<div class="content">

<h1>Worship Dance Steps</h1>

<div class="search-box">

<form method="GET">

<input type="text" name="search" placeholder="Search worship step">

<button type="submit">Search</button>

</form>

</div>

<div class="video-grid">

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div class="card">

<h3><?php echo $row['step_name']; ?></h3>

<iframe src="<?php echo $row['youtube_link']; ?>" allowfullscreen></iframe>

<p><?php echo nl2br($row['description']); ?></p>

</div>

<?php } ?>
    
</div>
    
<div class="pagination">

<?php if ($page > 1) { ?>
    <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>">
        Previous
    </a>
<?php } ?>

<?php
for($i = 1; $i <= $totalPages; $i++){
?>
    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"
       class="<?php if($page == $i) echo 'active'; ?>">
       <?php echo $i; ?>
    </a>
<?php
}
?>

<?php if ($page < $totalPages) { ?>
    <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>">
        Next
    </a>
<?php } ?>

</div>

</div>

</body>
</html>