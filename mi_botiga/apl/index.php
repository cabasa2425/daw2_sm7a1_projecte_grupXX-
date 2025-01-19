<?php
require('functions.php');

session_start();

if (!isset($_SESSION['id'])) {
    echo "Error: No se ha configurado el ID del cliente en la sesión.";
} else {
    echo "ID del cliente en la sesión: " . $_SESSION['id'];
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/home.css">
    <title>Wilos</title>
</head>
<body>
    
    <header>
        <div class="header-left">
            <img src="../V-removebg-preview.png">
            <h1>Wilos Balvan</h1>
        </div>
         <div class="header-right">
            <?php if (isset($_SESSION['usr'])): ?>
                <a class="link" href="auth.php?filter=logout">Logout</a>
                <?php if ($_SESSION['type'] === 'admin'): ?>
                    <a class="link" href="auth.php?filter=registerManager">Create a manager</a>
                    <a class="link" href="auth.php?filter=registerClient">Create a client</a>
                    <a class="link" href="auth.php?filter=modifyAdmin">Modify admin information</a>
                <?php endif; ?>
                <?php if ($_SESSION['type'] === 'manager'): ?>
                    <a class="link" href="manager.php?filter=list">Client list</a>
                    <a class="link" href="manager.php?filter=reportClient">Report</a>
                    <a class="link" href="manager.php?filter=registerProduct">Register product</a>
                    <a class="link" href="manager.php?filter=listProduct">List products</a>
                    <a class="link" href="manager.php?filter=viewCommand">View Commands</a>
                <?php endif;?>
            <?php else: ?>
                <a class="link" href="auth.php?filter=login">Login</a>
                <a class="link" href="behaviour.html">Behaviour</a>
            <?php endif; ?>
        </div>


    </header>

    <main>
        <?php if ($_SESSION['type'] === 'admin'): ?>
        <h1>Admin page</h1>
        <?php endif; ?>

        <section>
            <h2>Welcome to Wilos Balvan!</h2>
            <p>An application to manage users and permissions efficiently.</p>
        </section>

        <?php if ($_SESSION['type'] === 'client'): ?>
        <form method="post" action="client.php?filter=showProduct">
            <input type="hidden" name="usr" value="<?php echo $_SESSION['usr']; ?>">
            <input type="hidden" name="type" value="<?php echo $_SESSION['type']; ?>">
            <button type="submit">Show Product</button>
        </form>

        <form method="post" action="client.php">
            <input type="hidden" name="id" value="<?php echo $_SESSION['id'];?>">
            <input type="textarea" name="body">
            <button name="solicitude" value="modifyDelete" type="submit">Solicitude to modify or erase my account</button>
        </form>

        <form method="post" action="client.php">
            <input type="hidden" name="id" value="<?php echo $_SESSION['id'];?>">
            <input type="textarea" name="body">
            <button name="report" value="deleteCommand" type="submit">Report to the manager</button>
        </form>

        <a href="client.php?filter=viewCart">View Cart</a>
        <a href="client.php?filter=profile">Profile</a>

        <?php endif; ?>
        </main>


</body>
</html>