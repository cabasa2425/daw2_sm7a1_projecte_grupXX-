<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/features.css">
    <title>Document</title>
</head>
<body>
    
</body>
</html>l

<form method="POST">

    <label>Change username:</label>
    <input type="text" name="new_usr">

    <label>Change password:</label>
    <input type="password" name="new_pwd">

    <label>Change email address:</label>
    <input type="text" name="new_email">

    <input type="submit" value="Update">
    <a href="logout.php">Logout</a>
</form>


<?php
session_start();

$filename = "user.txt";

$usr = $_SESSION['usr']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_usr = $_POST['new_usr'];

    if (strlen($_POST['new_pwd']) < 8) {
        echo "<script>
                document.body.innerHTML += `<div style='color: red; font-size: 14px; margin-top: 10px;'>La contraseña debe tener al menos 8 caracteres.</div>`;
              </script>";
        exit();
    }
    
    $new_pwd = $_POST['new_pwd'];
    $new_email = $_POST['new_email'];

    if (empty($usr)) {
        die("No user is logged in.");
    }

    if (empty($new_usr) || empty($new_pwd) || empty($new_email)) {
        die("All fields are required.");
    }

    $file = fopen($filename, "r+");

    $new_lines = [];
    $updated = false;

    while (($line = fgets($file)) !== false) {
        $parts = explode(':', trim($line));

        if ($parts[0] === $usr) { 
            $line = "$new_usr:$new_pwd:$parts[2]:$new_email\n"; 
            $updated = true;
            $_SESSION['usr'] = $new_usr; 
        }

        $new_lines[] = $line; 
    }

    fclose($file);

    if ($updated) {
        file_put_contents($filename, implode("", $new_lines));
        echo "User details updated successfully.";
        header('location: admin.php');
    } else {
        echo "User not found.";
    }
}

?>
