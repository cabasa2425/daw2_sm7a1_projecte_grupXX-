<?php
require('functions.php');

session_start();

if (isset($_GET['filter']) && $_GET['filter'] === 'logout') {
    logout();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? "";
    $pwd = $_POST['pwd'];
    $id = 0;
    $email = $_POST['email'] ?? "";
    $surname = $_POST['surname'] ?? "";
    $phone = $_POST['phone'] ?? "";
    $type = null;
    $usr = $_POST['usr'] ?? "";

    $pdf = $_POST['pdf'] ?? "";
    $delete = $_POST['delete'] ?? "";
    $id = $_POST['id'] ?? '';
    
    $filter = $_POST['action'];
    
    if ($delete === 'client') return deleteClient();
    if ($delete === 'manager') return deleteManager();

    if ($filter === 'login') {
        if (empty($usr) || empty($pwd)) {
            echo "All fields must be filled";
        } else {
            if (login($usr, $pwd)) {
                echo "Login successful";
                header('location: index.php');
            } else {
                $mensaje = true;
            }
        }
    } elseif ($filter === 'registerManager') {

        if (empty($usr) || empty($pwd) || empty($email) || empty($name) || empty($surname)) {
            echo "All fields must be filled";
        }

        registerManager($usr, $pwd, 'manager', $email, $id, $name, $surname);
        header('location: auth.php?filter=registerManager');

    } elseif ($filter === 'registerClient') {

        if (empty($usr) || empty($pwd) || empty($email) || empty($name) || empty($surname) || empty($phone)) {
            echo "All fields must be filled";
        }

        registerClient($usr, $pwd, 'client', $email, $id, $name, $surname, $phone);
        header('location: auth.php?filter=registerClient');
    } elseif ($filter === 'modifyAdmin') {
        updateAdminInformation();
    }
    else {
        echo "Invalid action.";
    }
}

if ($pdf === "client") {
    ClientToPDF();
} elseif ($pdf === "manager") {
    ManagerToPDF();
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/features.css">
    <link rel="stylesheet" type="text/css" href="../css/lists.css">
    <title>Authentication</title>
</head>
<body>

<header>
        <div class="header-left">
            <img src="../V-removebg-preview.png">
            <h1>Wilos Balvan</h1>
        </div>
         <div class="header-right">
            <?php if (isset($_SESSION['usr'])): ?>
                <a href="auth.php?filter=logout">Logout</a>
                <a href="auth.php?filter=registerManager">Create a manager</a>
                <a href="auth.php?filter=registerClient">Create a client</a>
            <?php else: ?>
                <a href="auth.php?filter=login">Login</a>
                <a href="behavier.html">Behavier</a>
            <?php endif; ?>
        </div>


    </header>

    <?php
    $filter = isset($_GET['filter']) ? $_GET['filter'] : null;

    if (!$filter) {
        echo "There is no filter";
    }
    ?>

    <?php if ($filter === 'login'): ?>
        <h2>Login</h2>
        <form action="auth.php?filter=login" method="POST">
            <label>Username:</label>
            <input type="text" name="usr">
            <label>Password:</label>
            <input type="password" name="pwd">
            <button type="submit" name="action" value="login">Login</button>
        </form>
        <?php if ($mensaje): ?> <h2>Wrong Credentials</h2> <?php endif; ?>

        <?php else: ?>
    <?php if (checkAdmin()): ?>

    <?php if ($filter === 'registerManager'): ?>
        <h2>Register as Manager</h2>
        <form action="auth.php" method="POST">
            <button type="submit" name="pdf" value="manager">Export to pdf</button>
        </form>
        <div class="class">
        <form action="auth.php" method="POST">
            <label>Username:</label>
            <input type="text" name="usr">
            <label>Password:</label>
            <input type="password" name="pwd">
            <br>
            <label>Email:</label>
            <input type="text" name="email">
            <br>
            <label>Name:</label>
            <input type="text" name="name">
            <label>Surname:</label>
            <input type="text" name="surname">
            <button class="register" type="submit" name="action" value="registerManager">Register</button>
        </form>
        </div>
        <?php showManagers(); ?>

    <?php elseif ($filter === 'registerClient'): ?>
        <h2>Register as Client</h2>
        <form action="auth.php" method="POST">
        <button type="submit" name="pdf" value="client">Export to pdf</button>
        </form>
        <div class="class">
        <form action="auth.php" method="POST">
            <label>Username:</label>
            <input type="text" name="usr">
            <label>Password:</label>
            <input type="password" name="pwd">
            <br>
            <label>Email:</label>
            <input type="text" name="email">
            <br>
            <label>Name:</label>
            <input type="text" name="name">
            <label>Surname:</label>
            <input type="text" name="surname">
            <label>Phone:</label>
            <input type="text" name="phone">
            <button class="register" type="submit" name="action" value="registerClient">Register</button>
        </form>
        </div>

        <?php showClients(); ?>

        <?php elseif ($filter === "modifyAdmin"): ?>
            <h2>Modify Admin Information</h2>
            <form action="auth.php" method="POST">
                <label>Username:</label>
                <input type="text" name="usr">
                <label>Password:</label>
                <input type="password" name="pwd">
                <label>Email</label>
                <input type="text" name="email">
                <button type="submit" name="action" value="modifyAdmin">Modify</button>
            </form>
            
        <?php endif;?>
    <?php endif; ?>
<?php endif;?>
</body>
</html>