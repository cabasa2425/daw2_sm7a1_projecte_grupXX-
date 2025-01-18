<?php 
require('functions.php');

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['assign'] === 'showProduct')){

    $productId = $_POST['idProduct'];
    $quantity = $_POST['quantity'];
    $clientId = $_SESSION['id'];
    $price = $_POST['price'];
    $iva = $_POST['iva'];
    $name = $_POST['name'];

    addToCart($clientId, $productId, $name, $price, $iva, $quantity);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if ($_GET['filter'] === 'showProduct'): ?>
    <?php showProductsToClient(); ?>
    <?php endif;?>

    <?php if ($_GET['filter'] === 'viewCart'): ?>
        <?php viewCart($_SESSION['id']);?>
        <form action="client.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $_SESSION['id'];?>">
            <button type="submit" name="assign" value="buy">Buy</button>
            <button type="submit" name="assign" value="create ">create command</button>
            <button type="submit" name="assign" value="erase">erase command</button>
        </form>
    <?php endif; ?>
</body>
</html>