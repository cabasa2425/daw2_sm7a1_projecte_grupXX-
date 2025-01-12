<?php

require('functions.php');

session_start();

if (!isset($_SESSION['usr']) || $_SESSION['type']!== 'manager'){
        header('Location: index.php');
    }

if (isset($_GET['filter']) && $_GET['filter'] === 'logout') {
    logout();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    if (isset($_POST['action']) && $_POST['action'] === 'reportClient'){
        if (empty($_POST['request']) || empty($_POST['report'])) {
            echo "Error: Todos los campos son obligatorios.";
            exit;
        }
        $user_id = $_POST['id'];
        $request = $_POST['request'];
        $report = $_POST['report'];
    
        if ($request === 'add'){
            $user_id = $user_id ?: 'Add new one';
        }
        sendEmail($user_id, $request, $report);
    }
    else if ($_POST['action'] === 'registerProduct'){
        $name = $_POST['name'];
        $price = $_POST['price'];
        $iva = $_POST['iva'];
        $available = $_POST['available'];
    
        registerProduct($name, null, $price, $iva, $available); 
        echo "Producto añadido correctamente.";
    } else if ($_POST['action'] === 'modifyProduct'){

        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $iva = $_POST['iva'];
        $available = $_POST['available'];

        modifyProduct($name, $id, $price, $iva, $available);

} else if ($_POST['action'] === 'productPDF'){
    productToPDF();
}
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

<?php if (isset($_GET['filter']) && $_GET['filter'] === 'list'): ?>

    <?php showClientsToManager(); ?>

<?php endif; ?>

<?php if (isset($_GET['filter']) && $_GET['filter'] === 'reportClient'): ?>

    <h1>Reportar Cliente</h1>
    <form action="manager.php" method="POST">
        <input type="hidden" name="action" value="reportClient">
        <input type="hidden" name="id" value="<?php echo $_POST['id'];?>">

        <label>¿Qué solicitud quieres hacer?</label><br>
        <input type="radio" id="modify" name="request" value="modify" required>
        <label for="modify">Modificar</label><br>
        
        <input type="radio" id="delete" name="request" value="delete">
        <label for="delete">Eliminar</label><br>
        
        <input type="radio" id="add" name="request" value="add">
        <label for="add">Añadir</label><br>
        
        <label for="report">Reporte:</label><br>
        <textarea id="report" name="report" rows="5" required></textarea><br>
        
        <button type="submit">Enviar Correo</button>
    </form>

<?php elseif (isset($_GET['filter']) && $_GET['filter'] === 'registerProduct'): ?>

    <h1>Add Product</h1>
    <form action="manager.php" method="POST">
    <input type="hidden" name="action" value="registerProduct">
    <label for="name">Nombre:</label>
    <input type="text" id="name" name="name"><br>
    
    <label for="price">Precio:</label>
    <input type="number" id="price" name="price"><br>

    <label for="iva">iva:</label>
    <input type="number" name="iva"><br>

    <label for="available">availability:</label>
    <input type="text" name="available"><br>

    <button type="submit">Add Product</button>
    </form>



<?php elseif (isset($_GET['filter']) && $_GET['filter'] ==='listProduct'): ?>

<form action="manager.php" method="POST">
<button type="submit" name="action" value="productPDF">Export to pdf</button>
</form>
<?php showProducts(); ?>


<?php elseif (isset($_GET['filter']) && $_GET['filter'] ==='modifyProduct'): ?>

    <h1>Modify Product</h1>
    <form action="manager.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>" >
    <label for="name">Nombre:</label>
    <input type="text" id="name" name="name"><br>
    
    <label for="price">Precio:</label>
    <input type="number" id="price" name="price"><br>

    <label for="iva">iva:</label>
    <input type="number" name="iva"><br>

    <label for="available">availability:</label>
    <input type="text" name="available"><br>

    <button type="submit" name="action" value="modifyProduct">Modify Product</button>
    </form>

<?php elseif (isset($_GET['filter']) && $_GET['filter'] === 'deleteProduct'):?>

    <?php deleteProduct($_POST['id']) ?>
<?php endif; ?>

</body>
</html>