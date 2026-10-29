<!-- Videos.php -->
<?php
//include "config.php";

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

// Pagination settings
$limit = 8;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$start = ($page - 1) * $limit;

/* DELETE VIDEO */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];

$stmt = mysqli_prepare($conn, "DELETE FROM videos WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

    header("Location: Videos.php?page=$page&search=" . urlencode($search));
    exit();
}

/* UPDATE VIDEO */
if(isset($_POST['update'])){
    $id = (int)$_POST['id'];
    $step_name = $_POST['step_name'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    $stmt = mysqli_prepare($conn,
        "UPDATE videos
         SET step_name = ?, description = ?, category = ?
         WHERE id = ?");

    mysqli_stmt_bind_param(
        $stmt,
        "sssi",
        $step_name,
        $description,
        $category,
        $id
    );

    mysqli_stmt_execute($stmt);

    header("Location: Videos.php");
    exit();
}

/* SEARCH */
$search = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $query = "SELECT * FROM videos
              WHERE step_name LIKE '%$search%'
              ORDER BY created_at DESC
              LIMIT $start, $limit";

    $countQuery = "SELECT COUNT(*) AS total
                   FROM videos
                   WHERE step_name LIKE '%$search%'";
} else {

    $query = "SELECT * FROM videos
              ORDER BY created_at DESC
              LIMIT $start, $limit";

    $countQuery = "SELECT COUNT(*) AS total
                   FROM videos";
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
    <title>Manage Videos</title>
</head>

<body>

<?php include "NavigationAdmin.php"; ?>

<div class="content">

    <h1>Manage Videos</h1>

    <?php if(isset($message)) echo "<p class='success'>$message</p>"; ?>

    <div class="search-box">

        <form method="GET">

            <input type="text"
                   name="search"
                   placeholder="Search video title"
                   value="<?php echo htmlspecialchars($search); ?>">

            <button type="submit">Search</button>

        </form>

    </div>

    <div class="video-grid">

        <?php while($row = mysqli_fetch_assoc($result)){ ?>

<div class="card">

    <form method="POST">

        <input type="hidden"
               name="id"
               value="<?php echo $row['id']; ?>">

        <!-- Title -->
        <input type="text"
               name="step_name"
               value="<?php echo $row['step_name']; ?>"
               class="full-input"
               required>

        <!-- Video -->
        <iframe src="<?php echo $row['youtube_link']; ?>"
                allowfullscreen>
        </iframe>

        <!-- Description -->
        <textarea name="description"
                  class="full-textarea"
                  rows="5"><?php echo $row['description']; ?></textarea>

        <!-- Category BELOW description -->
        <select name="category" class="full-input">

            <option value="Praise"
                <?php if($row['category']=="Praise") echo "selected"; ?>>
                Praise
            </option>

            <option value="Worship"
                <?php if($row['category']=="Worship") echo "selected"; ?>>
                Worship
            </option>

            <option value="Praise Songs"
                <?php if($row['category']=="Praise Songs") echo "selected"; ?>>
                Praise Songs
            </option>

            <option value="Worship Songs"
                <?php if($row['category']=="Worship Songs") echo "selected"; ?>>
                Worship Songs
            </option>

        </select>

        <button type="submit" name="update">
            Update
        </button>

        <a href="Videos.php?delete=<?php echo $row['id']; ?>"
           onclick="return confirm('Are you sure you want to delete this video?')">

            <button type="button">
                Delete
            </button>

        </a>

    </form>

</div>

<?php } ?>

    </div>

    <?php if ($totalPages > 1) { ?>

<div class="pagination">

    <?php if ($page > 1) { ?>
        <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>">
            Previous
        </a>
    <?php } ?>

    <?php for($i = 1; $i <= $totalPages; $i++){ ?>

        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"
           class="<?php if($page == $i) echo 'active'; ?>">
            <?php echo $i; ?>
        </a>

    <?php } ?>

    <?php if ($page < $totalPages) { ?>
        <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>">
            Next
        </a>
    <?php } ?>

</div>

<?php } ?>
    
</div>

</body>
</html>