<div class="sidebar" id="sidebar">
    <div class="hamburger" id="hamburger">&#9776;</div> <!-- hamburger menu -->
    <h2>Admin Panel</h2>

    <a href="AddVideo.php">Add Video</a>
    <a href="Videos.php">Videos</a>
    <a href="AddDancer.php">Add Dancer</a>
    <a href="Profile.php">My Profile</a>
    <a href="Logout.php">Logout</a>
</div>

<div class="content">
    <!-- page content here -->
</div>

<script>
const sidebar = document.getElementById('sidebar');
const hamburger = document.getElementById('hamburger');

hamburger.addEventListener('click', () => {
    sidebar.classList.toggle('active');
});
</script>

</body>