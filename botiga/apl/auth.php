<?php
session_start();



if (!isset($_SESSION['usr']) && !isset($_SESSION['pwd'])) {
    echo "<h2>Log in to be access to the website</h2>";
    echo "Wrong password or username.";
    echo "<br><a href='index.html'>Home page</a>";
    echo "<br><a href='login.php'>Go to login</a>";

    exit();
}

$usr = $_SESSION['usr'];
$type = $_SESSION['type'];


echo "<h2>Benvingut, $usr</h2>";

if ($type == "admin") {
    echo "<h3>Admin page</h3>";
    // header("location: admin.php");
} elseif ($type == "manager") {
    echo "<h3>Manager page</h3>";
    // header("location: manager.php");
} else {
    echo "<h3>Client page</h3>";
    // header("Location: client.php");
}

echo '<br><a href="logout.php">Logout</a>';
?>
