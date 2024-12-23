<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usr = $_POST['usr'] ?? '';
    $pwd = $_POST['pwd'] ?? '';
    $type = $_POST['type'] ?? '';
    
    if (empty($usr) || empty($pwd) || empty($type)) {
        die("All fields are required.");
    }
    
    $filename = "user.txt";
    $data = "$usr:$pwd:$type\n";
    $adminExists = false;
    
    if (file_exists($filename)) {
        $file = fopen($filename, "r");
    
        while (($line = fgets($file)) !== false) {
            if (strpos($line, ":admin") !== false) {
                $adminExists = true;
                break;
            }
        }
        fclose($file);
    } else {
        $file = fopen($filename, "w");
    }
    
    if ($adminExists && $type === "admin") {
        die("An admin already exists.");
    }
    
    $file = fopen($filename, "a");
    if (!$file) {
        die("Error opening or creating the file.");
    }
    
    fwrite($file, $data);
    fclose($file);
    
    echo '<script type="text/javascript">alert("Registration successful. User data saved.");</script>';

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/features.css">
    <title>Login</title>
</head>
<body>
    <header>
        <div class="header-left">
            <img src="../V-removebg-preview.png">
            <h1>Wilos Balvan</h1>
        </div>
    </header>

    <form class="form" action="register.php" method="POST">

        <label>Username:</label>
        <input type="text" name="usr" required>

        <label>Password:</label>
        <input type="password" name="pwd" required>
        <br>
        <label>What are you?</label>
        <br>

        <input type="radio" name="type" value="client" required>  Client<br>
        <input type="radio" name="type" value="manager" required>  Manager<br>
        <input type="radio" name="type" value="admin" required>  Administrator<br>
        <button class="login" type="submit">Register</button>
        <a href="index.html">Home page</a>
    </form>

</body>
</html>
