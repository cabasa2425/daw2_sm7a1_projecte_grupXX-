<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/features.css">
    <title>ListClients</title>
</head>
<body>
<header>
    <div class="header-left">
        <img src="../V-removebg-preview.png" alt="Logo">
        <h1>Wilos Balvan</h1>
    </div>
    <div class="header-right">
        <a href="logout.php">Logout</a>
        <a href="change_admin_data.php">Change admin data</a>
    </div>
</header>

<a href="create_client.php">Create Client</a>
<a href="clients_pdf.php" style="margin-left: 20px;">Download User List as PDF</a>

<div>
<?php
    $filename = "user.txt";

    if (file_exists($filename)) {
        $file = fopen($filename, "r");
        $i = 0;
        $l = 0;

        while (($line = fgets($file)) !== false) {
            $parts = explode(':', trim($line));

            if ($l === 0) {
                $l += 1;
            } else {
                if ($parts[2] === 'client') {
                    echo "<p>Username: $parts[0]</p>";
                    echo "<p>Email: $parts[3]</p>";
                    echo "<p>Name: $parts[5] $parts[6]</p>";
                    echo "<p>Id: $parts[4]</p>";
                    echo "<hr>";
                }
            }
        }
        fclose($file);
    } else {
        echo "<p>No users found.</p>";
    }
?>
</div>

</body>
</html>
