<?php
session_start();

if (!isset($_SESSION['usr']) || !isset($_SESSION['pwd'])) {
    echo "<h2>Log in to access the website</h2>";
    echo "Wrong password or username.";
    echo "<br><a href='index.html'>Home page</a>";
    echo "<br><a href='login.php'>Go to login</a>";
    exit();
}

$usr = $_SESSION['usr'];
$type = $_SESSION['type'];

echo "<h2>Welcome, $usr</h2>";

$filename = "user.txt";

$file = fopen($filename, "r");
$admin = false;

while (($line = fgets($file)) !== false) {
    list($us, $pw, $ty, $em) = explode(':', trim($line));
        if ($us === $usr) {

            if (strpos($ty, "admin")!== false) {
                $admin = true;
                break;
            }
        }
  
    }

   


fclose($file);

if ($admin === true) {
    header('Location: admin.php');
    exit();
} elseif ($type == "manager") {
    echo "<h3>Manager page</h3>";
} else {
    echo "<h3>Client page</h3>";
}

echo '<br><a href="logout.php">Logout</a>';
?>
