<?php 
require('functions.php');

session_start();

if ($_SESSION['type'] !== 'client') {
    header('Location: index.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['assign'] === 'showProduct')){

    $productId = $_POST['idProduct'];
    $quantity = $_POST['quantity'];
    $clientId = $_SESSION['id'];
    $price = $_POST['price'];
    $iva = $_POST['iva'];
    $name = $_POST['name'];

    addToCart($productId, $name, $price, $iva, $quantity);

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['assign'] === 'buy')) {
    createCommand($_SESSION['usr']);
    eraseCart($_SESSION['usr']);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['assign'] === 'erase')) {
    eraseCart($_SESSION['usr']);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['assign'] === 'eraseCommand')) {
    eraseCommand($_SESSION['usr']);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['assign'] === 'pdf') {
    commandToPDF($_SESSION['usr']);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['assign'] === 'modifyCommand'){
    modifyCommand($_SESSION['usr']);
    eraseCommand($_SESSION['usr']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['assign'] === 'deleteProduct') {
    $productId = $_POST['idProduct'];
    deleteProductFromCart($_SESSION['usr'] ,$productId);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['assign'] === 'changeQuantity') {
    $productId = $_POST['idProduct'];
    changeQuantity($_SESSION['usr'] ,$productId, $_POST['changeQuantity']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['solicitude'] === 'modifyDelete'){
    $subject = "Solicitude de modificación o eliminación de cuenta";
    $body = $_POST['body'];
    sendEmailToManager($_SESSION['id'], $subjetc, $body);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['report'] === 'deleteCommand'){
    $subject = "etició de justificació de comanda rebutjada";
    $body = $_POST['body'];
    sendEmailToManager($_SESSION['id'], $subjetc, $body);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/home.css">
    <title>Document</title>
</head>
<header>
        <div class="header-left">
            <a class="home" href="index.php">
            <img src="../V-removebg-preview.png">
            </a>
            <h1>Wilos Balvan</h1>
        </div>

    </header>
<body>
    <?php if ($_GET['filter'] === 'showProduct'): ?>
    <?php showProductsToClient(); ?>
    <a href="client.php?filter=viewCart">View Cart</a>
    <?php endif;?>

    <?php if ($_GET['filter'] === 'viewCart'): ?>
        <?php viewCart($_SESSION['usr']);?>
        <form action="client.php?filter=viewCart" method="POST">
            <input type="hidden" name="id" value="<?php echo $_SESSION['id'];?>">
            <button type="submit" name="assign" value="buy">Buy</button>
            <button type="submit" name="assign" value="erase">erase cart</button>
            <button type="submit" name="assign" value="pdf">Download command</button>
            <button type="submit" name="assign" value="eraseCommand">erase command</button>
            <button type="submit" name="assign" value="modifyCommand">Modify command</button>
        </form><br><br>
        <a class="link" href="client.php?filter=showProduct">Back to product list</a>
    <?php endif; ?>

    <?php if ($_GET['filter'] === 'profile'):?>
        <?php showDataClient(); ?>
    <?php endif;?>
</body>
</html>