<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/features.css">
    <link rel="stylesheet" type="text/css" href="../css/lists.css">
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

<form action="export_pdf.php" method="POST">
    <button type="submit">Exportar a PDF</button>
</form>

<div class="lists">
    <?php
    $filename = "user.txt";

    if (file_exists($filename)) {
        $file = fopen($filename, "r");

        while (($line = fgets($file)) !== false) {
            $parts = explode(':', trim($line));

            if ($parts[2] === "manager") {
                
                echo "<div class='list'>";
                echo "<a href=edit.manager.php>";
                echo "<h3><strong>Nombre:</strong> $parts[0]</h3>";
                echo "<p><strong>Usuario:</strong> $parts[5]</p>";
                echo "<p><strong>Apellido:</strong> $parts[6]</p>";
                echo "<p><strong>Email:</strong> $parts[3]</p>";
                echo "<p><strong>ID:</strong> $parts[4]</p>";
                echo "<form action'list_managers.php' method='POST'><button> delete </button></form>";
                echo "</div>";
                echo "</a>";
            }
        }
        fclose($file);
    }
    
    ?>
</div>

</body>
</html>
