<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$filename = 'user.txt';
$file = fopen($filename, 'r');

if ($file) {

    $usr = $_POST['usr'];
    $pwd = $_POST['pwd'];

    while (($line = fgets($file)) !== false){
        $parts = explode(':', trim($line));
        if ($parts[0] === $usr && $parts[1] === $pwd){
            session_start();
            $_SESSION['usr'] = $parts[0];
            $_SESSION['pwd'] = $parts[1];
            $_SESSION['type'] = $parts[2];
            header('Location: auth.php');
            fclose($file);
            exit();
        }           
    }

        fclose($file);
        header('location: auth.php');
        exit();
    


} else {
    echo "Error opening the file.";
    exit(); 
}
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

    <form class="form" action="login.php" method="POST">

        <label>Username:</label>
        <input type="text" name="usr" required>

        <label>Password:</label>
        <input type="password" name="pwd" required>
        <br>
        <button class="login" type="submit">Login</button>
        <a href="index.html">Home page</a>
    </form>


</body>
</html>