<!-- Hamburger button -->
<div class="hamburger" id="hamburger">&#9776;</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h2>Rebana Library</h2>

    <a href="Home.php">Home</a>
    <a href="Praise.php">Praise Steps</a>
    <a href="Worship.php">Worship Steps</a>
    <a href="PraiseSongs.php">Praise with Songs</a>
    <a href="WorshipSongs.php">Worship with Songs</a>
    <a href="Profile.php">My Profile</a>
    <a href="Logout.php">Logout</a>
</div>

<div class="content">
    <!-- page content here -->
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

const sidebar = document.getElementById("sidebar");
const hamburger = document.getElementById("hamburger");

hamburger.addEventListener("click", function(){
    sidebar.classList.toggle("active");
});

});
</script>

</body>