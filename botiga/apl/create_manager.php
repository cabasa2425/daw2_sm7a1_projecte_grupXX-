<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usr = $_POST['usr'] ?? '';
    $pwd = $_POST['pwd'] ?? '';
    $type = $_POST['type'] ?? '';
    $email = $_POST['email'] ?? '';
    $id = 0;
    $username = $_POST['username'] ?? '';
    $surname = $_POST['surname'] ?? '';

    
    $filename = "user.txt";
    
    if (file_exists($filename)) {
        $file = fopen($filename, "r");
        $l = 0;
        while (($line = fgets($file)) !== false) {
            $l += 1;
        }
        fclose($file);
        $id = $l+1;
    } else {
        $file = fopen($filename, "w");
    }
    
    $file = fopen($filename, "a");
    if (!$file) {
        die("Error opening or creating the file.");
    }

    $data = "$usr:$pwd:$type:$email:$id:$username:$surname\n";
    fwrite($file, $data);
    fclose($file);
    
    echo '<script type="text/javascript">alert("Registration successful. User data saved.");</script>';
    header('location: admin.php');
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

    <form action="create_manager.php" method="POST">

        <label>Username:</label>
        <input type="text" name="usr" required>

        <label>Password:</label>
        <input type="password" name="pwd" required>
        <br>
        <label>What are you?</label>
        <br>
        <input type="radio" name="type" value="manager" required>  Manager<br>
        <br>
        <label>Email:</label>
        <input type="text" name="email" required>
        <br>
        <label>name:</label>
        <input type="text" name="username" required>
        <label>surname</label>
        <input type="text" name="surname" required>
        <button type="submit">Register</button>
    </form>

</body>
</html>
