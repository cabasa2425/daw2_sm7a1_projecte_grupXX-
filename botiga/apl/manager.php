<?php

require('functions.php');

session_start();

if (isset($_GET['filter']) && $_GET['filter'] === 'logout') {
    logout();
}

?>

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

<?php if ($_GET['filter'] === 'list'): ?>

<?php showClients() ?>

<?php endif;?> 

</body>
</html>

