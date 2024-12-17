<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/features.css">
    <title>Document</title>
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
    <a href="create_manager.php">Create Manager</a>

    <div><?php
    
    $filename = "user.txt";

    if (file_exists($filename)) {
        $file = fopen($filename, "r");
        $i = 0;
        $l = 0;

        while (($line = fgets($file))!== false) {
            $parts = explode(':', trim($line));
            if ($l === 0) {
                $l += 1;
            } else {
                for (; $i < count($parts); $i++) {
                    if ($i === 1){
                        
                    } else {
                        echo "<p>$parts[$i]</p>";
                    }
                }
            }
            

            
        }
    }
    fclose($file);
    
    
    
    ?></div>

    

</body>
</html>

