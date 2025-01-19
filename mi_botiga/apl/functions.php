<?php

function readLines($filename){
    if (file_exists($filename)){
        $size = filesize($filename);

        if ($size > 0){
            $file = fopen($filename, 'r');
            $lines = explode(PHP_EOL, fread($file, $size));
            fclose($file);
            return $lines;
        } else {
            return [];
        }
    } else {
        echo "no encontrado el file";
        die();
    }
}

function login($name, $password){
    $filename = '../users.txt';
    $lines = readLines($filename);
    foreach ($lines as $line){
        $parts = explode(":", $line);
            if ($parts[0] === $name){

            if (password_verify($password, $parts[1])){
                session_start();
                $_SESSION['usr'] = $name;
                $_SESSION['type'] = $parts[2]; 
                $_SESSION['id'] = $parts[4];
                return true; 
            } else {
                return false;
            }
        }
    }
}

function logout(){
    session_start();
    session_destroy();
    header('Location: index.php');
}

function registerManager($usr,$pwd,$type,$email,$id,$name,$surname) {

    $filename = '../users.txt';
    if (file_exists($filename)){

        $lines = readLines($filename);
        $id = 0;
        foreach ($lines as $line){
            $id += 1;
        }
        $id += 1;
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        $file = fopen($filename, 'a');
        $data = "$usr:$hashedPwd:$type:$email:$id:$name:$surname\n";
        fwrite($file, $data);
        fclose($file); 
    } else {
        echo "<script> body.innerHTML = 'File not found'</script>";
        return;
    }
}

function registerClient($usr,$pwd,$type,$email,$id,$name,$surname,$phone){
    
    $filename = '../users.txt';
    if (file_exists($filename)){

        $lines = readLines($filename);
        $id = 0;
        foreach ($lines as $line){
            $id += 1;
        }
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        $file = fopen($filename, 'a');
        $data = "$usr:$hashedPwd:$type:$email:$id:$name:$surname:$phone\n";
        fwrite($file, $data);
        fclose($file);    
    } else {
        echo "<script> body.innerHTML = 'File not found'</script>";
        return;
    }
}


function showManagers(){
            $filename = '../users.txt';

            $lines = readLines($filename);
            foreach ($lines as $line){

                $parts = explode(':', trim($line));

                if ($parts[2] === "manager") {
                    
                    echo "<div class='list'>";
                    echo "<h3><strong>Nombre:</strong> $parts[0]</h3>";
                    echo "<p><strong>Usuario:</strong> $parts[5]</p>";
                    echo "<p><strong>Apellido:</strong> $parts[6]</p>";
                    echo "<p><strong>Email:</strong> $parts[3]</p>";
                    echo "<p><strong>ID:</strong> $parts[4]</p>";
                    echo "<form action='auth.php?filter=registerManager' method='POST'>
                    <input type='hidden' name='id' value='$parts[4]'>
                    <button  name='assign' value='deleteManager'> delete </button></form>";
                    echo "<form action='auth.php?filter=modifyManager' method='POST'>
                    <input type='hidden' name='id' value='$parts[4]'>
                    <button  name='assign' value='modify_manager'> Modify </button></form>";
                    echo "</div>";
                }
            }
    }
    
    function showClients(){
        $filename = '../users.txt';

        $lines = readLines($filename);
        foreach ($lines as $line){

            $parts = explode(':', trim($line));

            if ($parts[2] === "client") {
                
                echo "<div class='list'>";
                echo "<h3><strong>Nombre:</strong> $parts[0]</h3>";
                echo "<p><strong>Usuario:</strong> $parts[5]</p>";
                echo "<p><strong>Apellido:</strong> $parts[6]</p>";
                echo "<p><strong>Email:</strong> $parts[3]</p>";
                echo "<p><strong>ID:</strong> $parts[4]</p>";
                echo "<form action='auth.php?filter= "  ."registerClient' method='POST'>
                <input type='hidden' name='id' value='$parts[4]'>
                <button  name='assign' value='deleteClient'> delete </button></form>";
                echo "<form action='auth.php?filter=modifyClient' method='POST'>
                <input type='hidden' name='id' value='$parts[4]'>
                <button  name='assign' value='modify_client'> Modify </button></form>";
                echo "</div>";
            }

        }    
}

function showClientsToManager(){
    $filename = '../users.txt';

    $lines = readLines($filename);
    foreach ($lines as $line){

        $parts = explode(':', trim($line));

        if ($parts[2] === "client") {
            
            echo "<div class='list'>";
            echo "<h3><strong>Nombre:</strong> $parts[0]</h3>";
            echo "<p><strong>Usuario:</strong> $parts[5]</p>";
            echo "<p><strong>Apellido:</strong> $parts[6]</p>";
            echo "<p><strong>Email:</strong> $parts[3]</p>";
            echo "<p><strong>ID:</strong> $parts[4]</p>";
            echo "<form action='manager.php?filter="  ."reportClient' method='POST'>
            <input type='hidden' name='id' value='$parts[4]'>
            <button  name='report' value='reportClient'> Report </button></form>";
        }

    }    
}

function modifyManager(){
    $usr = $_POST['usr'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];

    $id = $_POST['id'];
    $filename = '../users.txt';

    $lines = readLines($filename);
    $newLines = [];
    foreach ($lines as $line){
        $parts = explode(':', trim($line));
        if ($parts[4]!== $id) {
            $newLines[] = $line;
        } else {
            $newLines[] = "$usr:$parts[1]:$parts[2]:$email:$parts[4]:$name:$surname";
        }
    }
    $file = fopen($filename, 'w');
    fwrite($file, implode(PHP_EOL, $newLines));
    array_pop($lines);
}

function modifyClient(){
    $usr = $_POST['usr'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $phone = $_POST['phone'];


    $id = $_POST['id'];
    $filename = '../users.txt';

    $lines = readLines($filename);
    $newLines = [];
    foreach ($lines as $line){
        $parts = explode(':', trim($line));
        if ($parts[4]!== $id) {
            $newLines[] = $line;
        } else {
            $newLines[] = "$usr:$parts[1]:$parts[2]:$email:$parts[4]:$name:$surname:$phone";
        }
    }
    $file = fopen($filename, 'w');
    fwrite($file, implode(PHP_EOL, $newLines));
    array_pop($lines);
}

function deleteManager(){
    $id = $_POST['id'];
    $filename = '../users.txt';

    $lines = readLines($filename);
    $newLines = [];
    foreach ($lines as $line){
        $parts = explode(':', trim($line));
        if ($parts[4]!== $id) {
            $newLines[] = $line;
        }
    }
    $file = fopen($filename, 'w');
    fwrite($file, implode(PHP_EOL, $newLines));
    array_pop($lines);
}
function updateAdminInformation() {
    $usr = $_POST['usr'];
    $pwd = $_POST['pwd'];
    $email = $_POST['email'];

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    $filename = '../users.txt';

    $lines = readLines($filename);
    foreach ($lines as $i => $line) {
        $parts = explode(':', trim($line));
        if ($parts[2] === 'admin') {
            $lines[$i] = "$usr:$hashedPwd:$parts[2]:$email";
            break;
        }
    }
    $file = fopen($filename, 'w');
    fwrite($file, implode(PHP_EOL, $lines));
    header('Location: index.php');
    exit();
}

function ManagerToPDF() {
    require_once('../vendor/autoload.php');
    $dompdf = new Dompdf\Dompdf();
    $filename = '../users.txt';

    $lines = readLines($filename);
    $html = "<h1>Listado de managers</h1> <table border='1' style='width:100%; border-collapse: collapse;'>
    <tr><th>Nombre</th><th>Usuario</th><th>Apellido</th><th>Email</th><th>ID</th></tr>";
    foreach ($lines as $line) {
        $parts = explode(':', trim($line));
        if ($parts[2] === "manager") {
            $html.= "<tr><td>$parts[0]</td><td>$parts[5]</td><td>$parts[6]</td><td>$parts[3]</td><td>$parts[4]</td></tr>";
        }
    }
        $html .= "</table>";
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Manager_list.pdf",);
}

function ClientToPDF() {
    require_once('../vendor/autoload.php');
    $dompdf = new Dompdf\Dompdf();
    $filename = '../users.txt';

    $lines = readLines($filename);
    $html = "<h1>Client list</h1>   <table border='1' style='width:100%; border-collapse: collapse;'>
    <tr><th>Name</th><th>User</th><th>Surname</th><th>Email</th><th>ID</th><th>Phone</th></tr>";
    foreach ($lines as $line) {
        $parts = explode(':', trim($line));
        if ($parts[2] === "client") {
            $html.= "<tr><td>$parts[0]</td><td>$parts[5]</td><td>$parts[6]</td><td>$parts[3]</td><td>$parts[4]</td><td>$parts[7]</td></tr>";
        }
    }
        $html .= "</table>";
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Client_list.pdf");
}

function deleteClient() {
    $id = $_POST['id'];
    $filename = '../users.txt';

    $lines = readLines($filename);
    $newLines = [];
    foreach ($lines as $line) {
        $parts = explode(':', trim($line));
        if ($parts[4]!== $id) {
            $newLines[] = $line;
        }
    }
    $file = fopen($filename, 'w');
    fwrite($file, implode(PHP_EOL, $newLines));
}

function updateManagerInformations() {
    $usr = $_POST['usr'];
    $pwd = $_POST['pwd'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $id = $_POST['id'];
    $filename = '../users.txt';

    $lines = readLines($filename);
    foreach ($lines as $i => $line) {
        $parts = explode(':', trim($line));
        if ($parts[4] !== $id) {
            $lines[$i] = "$usr:$pwd:$parts[2]:$email:$parts[4]:$name:$surname";
            break;
        }
    }
    $file = fopen($filename, 'w');
    fwrite($file, implode("\n", $lines));
    fclose($file);
}

// SECTION 3 ----------------------------------------


require('../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function sendEmail($user_id, $request, $report){

    if ($request === 'add'){
        $user_id = $user_id ?: 'Add new one';
    }
    // Configuración del correo
    $mail = new PHPMailer(true);

    try {
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Cambiar a DEBUG_OFF para producción
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        // Credenciales del correo
        $mail->Username = 'tenedote999@gmail.com';  // Cambia por tu correo de Gmail
        $mail->Password = 'erjx bpdw njvr nodq';    // Cambia por tu contraseña de aplicación

        // Configuración del destinatario y mensaje
        $mail->setFrom('tenedote999@gmail.com', 'Carlos');
        $mail->addAddress('tenedote999@gmail.com', 'Carlos');  // Cambia por el correo del destinatario

        $mail->Subject = "Reporte de Cliente - Solicitud: " . ucfirst($request);  // Asunto del correo
        $mail->isHTML(true);
        $mail->Body = "<h1>Nuevo Reporte</h1>
                       <p><strong>ID del Usuario:</strong> {$user_id}</p>
                       <p><strong>Solicitud:</strong> {$request}</p>
                       <p><strong>Reporte:</strong><br>{$report}</p>";

        // Enviar correo
        if ($mail->send()) {
            echo 'El correo se ha enviado correctamente.';
            header('location: index.php');
        } else {
            echo 'No se pudo enviar el correo. Inténtalo nuevamente.';
        }
    } catch (Exception $e) {
        echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
    }
}

function registerProduct($name, $id, $price, $iva, $available){
    
    $filename = '../products.txt';
    if (file_exists($filename)){

        $lines = readLines($filename);
        $id = 0;
        foreach ($lines as $line){
            $id += 1;
        }

        $file = fopen($filename, 'a');
        $data = "$name:$id:$price:$iva:$available\n";
        fwrite($file, $data);
        fclose($file);    
    } else {
        echo "File not found";
        return;
    }
}

function showProductsToManager(){
    $filename = '../products.txt';

    if (file_exists($filename)){

        $lines = readLines($filename);
        foreach ($lines as $line){
            if (!empty($line)) {
            $parts = explode(':', $line);
            echo "<p>ID: {$parts[1]}, Nombre: {$parts[0]}, Precio: {$parts[2]}, IVA: {$parts[3]}, Disponible: {$parts[4]}</p>";
            echo "<form action='manager.php?filter=deleteProduct' method='POST'>
                    <input type='hidden' name='id' value='$parts[1]'>
                    <button  name='assign' value='deleteProduct'> Delete </button></form>";

            echo "<form action='manager.php?filter=modifyProduct' method='POST'>
                    <input type='hidden' name='id' value='$parts[1]'>
                    <button  name='assign' value='modifyProduct'> Modify </button></form>";
            }
        }
    }
}

function modifyProduct($name, $id, $price, $iva, $available){
    $filename = '../products.txt';

    if (file_exists($filename)){

        $lines = readLines($filename);
        foreach ($lines as $i => $line){
            $parts = explode(':', $line);
            if ($parts[1] === $id){
                $lines[$i] = "$name:$id:$price:$iva:$available";
                break;
            }
        }
        $file = fopen($filename, 'w');
        fwrite($file, implode("\n", $lines));
        fclose($file);    
        header('location: manager.php?filter=listProduct');
    } else {
        echo "File not found";
        return;
    }
}

function deleteProduct($id){

    $filename = '../products.txt';
    if (file_exists($filename)){
        $lines = readLines($filename);
        $newLines = [];
        foreach ($lines as $line){
            $parts = explode(':', $line);
            if ($parts[1]!== $id) {
                $newLines[] = $line;
            }
        }
        $file = fopen($filename, 'w');
        fwrite($file, implode("\n", $newLines));
        header('location: manager.php?filter=listProduct');
    } else {
        echo "File not found";
        return;
    }
}

function productToPDF(){
        require_once('../vendor/autoload.php');
        $dompdf = new Dompdf\Dompdf();
        $lines = readLines('../products.txt');
        $html = "<h1>List of products</h1>   <table border='1' style='width:100%; border-collapse: collapse;'>
        <tr><th>NAME</th><th>ID</th><th>PRICE</th><th>IVA</th><th>AVAILABILITY</tr>";
        foreach ($lines as $line) {
            $parts = explode(':', trim($line));
            $html.= "<tr><td>$parts[0]</td><td>$parts[1]</td><td>$parts[2]</td><td>$parts[3]</td><td>$parts[4]</td></tr>";
        }
            $html .= "</table>";
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $dompdf->stream("Products_list.pdf"); 
}

// SECTION 4 -----------------------------------------------------
function showProductsToClient(){
    $filename = '../products.txt';

    if (file_exists($filename)){

        $lines = readLines($filename);
        foreach ($lines as $line){
            if (!empty($line)) {
            $parts = explode(':', $line);
            echo "<p>ID: {$parts[1]}, Nombre: {$parts[0]}, Precio: {$parts[2]}, IVA: {$parts[3]}, Disponible: {$parts[4]}</p>";
            if ($parts[4] === 'yes') {
            echo "<form action='client.php?filter=showProduct' method='POST'>
                    <input type='hidden' name='idClient' value='{$_SESSION['id']}'>
                    <input type='hidden' name='idProduct' value='$parts[1]'>
                    <input type='number' name='quantity'>
                    <input type='hidden' name='price' value='$parts[2]'>
                    <input type='hidden' name='iva' value='$parts[3]'>
                    <input type='hidden' name='name' value='$parts[0]'>
                    <button  name='assign' value='showProduct'> Add </button></form>";
            }
            }
        }
    }
}

function addToCart($idProduct, $name, $price, $iva, $quantity) {

    $filename = '../cistelles/' . $_SESSION['usr'] . '.txt';
    $file = fopen($filename, file_exists($filename) ? 'a' : 'w');
    $lines = readLines($filename);
    $productExists = false;

    foreach ($lines as $line) {
        if (!empty($line)) {
            $parts = explode(':', $line);
            if ($parts[0] === $idProduct) {
                $productExists = true;
                break;
            }
        }
    }

    if (!$productExists) {
        $data = "$idProduct:$name:$price:$iva:$quantity\n";
        $file = fopen($filename, 'a');
        fwrite($file, $data); 
        fclose($file);
    }
}

function viewCart($usr) {
    $filename = '../cistelles/'. $usr .'.txt';
    if (file_exists($filename)){

        $lines = readlines($filename);
        $totalWithIVA = 0;
        $totalWithoutIVA = 0;
        $onlyIVA = 0;

        echo date("Y-m-d H:i:s");
        foreach ($lines as $line){
            if (!empty($line)) {
            $parts = explode(':', $line);
                $totalWithIVA +=($parts[2] + ($parts[2]*$parts[3])/100)*$parts[4];
                $totalWithoutIVA += $parts[2]*$parts[4];
                $onlyIVA += (($parts[2]*$parts[3])/100)*$parts[4];

                echo "<form action='client.php?filter=viewCart' method='POST'>
                <input type='hidden' name='idProduct' value='$parts[0]'>
                <input type='number' name='changeQuantity' placeholder='changeQuantity'>
                <button  name='assign' value='changeQuantity'> change </button>
                <button  name='assign' value='deleteProduct'> delete </button></form>";

                echo "<p> Product : " . $parts[1] . "</p>";
                echo "<p> Price: " . $parts[2] . "</p>";
                echo "<p> Iva : " . $parts[3] . "</p>";
                echo "<p> Quantity : " . $parts[4] . "</p>";  
                echo "<p> without iva : " . $parts[2]*$parts[3] . "</p>";
                echo "<p> only iva " . (($parts[2]*$parts[3])/100)*$parts[4];
                echo "<p> Preu with iva : " . ($parts[2] + ($parts[2]*$parts[3])/100)*$parts[4] . "</p>" . "\n";                
            }
        }
        }
    echo "<p>-------------------</p>";
    echo "<p> Total without iva: " . ($totalWithoutIVA) . "</p>";
    echo "<p> Total in iva: " . ($onlyIVA) . "</p>";

    echo "<p> Total with iva: " . ($totalWithIVA) . "</p>";
}

function deleteProductFromCart($usr, $idProduct) {
    $filename = '../cistelles/' . $usr . '.txt';
    if (file_exists($filename)){

        $lines = readLines($filename);
        $newLines = [];
        foreach ($lines as $line){
            $parts = explode(':', $line);
            if ($parts[0]!== $idProduct) {
                $newLines[] = $line;
            }
        }
        $file = fopen($filename, 'w');
        fwrite($file, implode("\n", $newLines));
        header('location: client.php?filter=viewCart');
    } else {
        echo "File not found";
        return;
    }
}

function eraseCart($usr) {
    $filename = '../cistelles/'. $usr . '.txt';
    if (file_exists($filename)){
        $newLines = [];
        $file = fopen($filename, 'w');
        fwrite($file, implode("\n", $newLines));
        header('location: client.php?filter=viewCart');
    } else {
        echo "File not found";
        return;
    }
}

function createCommand($usr) {
    $filename = '../comandes/'. $usr . '.txt';

    $lines = readLines('../cistelles/'. $usr. '.txt');
        $newLines = [];
        foreach ($lines as $line){
            $newLines[] = $line;
        }

        $file = fopen($filename, 'w');
        fwrite($file, implode("\n", $newLines));
        header('location: client.php?filter=viewCart');
}

function modifyCommand($usr) {
    $filename = '../cistelles/'. $usr . '.txt';

    $lines = readLines('../comandes/'. $usr. '.txt');
        $newLines = [];
        foreach ($lines as $line){
            $newLines[] = $line;
        }

        $file = fopen($filename, 'w');
        fwrite($file, implode("\n", $newLines));
        header('location: client.php?filter=viewCart');
}

function eraseCommand($usr) {
    $filename = '../comandes/' . $usr . '.txt';
    if (file_exists($filename)) {
        if (unlink($filename)) {
            header('location: client.php?filter=viewCart');
        } else {
            echo "Error: No se pudo eliminar el archivo.";
            return;
        }
    } else {
        echo "Error: Archivo no encontrado.";
        return;
    }
}


function commandToPDF($usr) {
    $filename = '../comandes/'. $usr . '.txt';
    require_once('../vendor/autoload.php');
    $dompdf = new Dompdf\Dompdf();
    $lines = readLines($filename);
    $html = "<h1>Comanda de ". $_SESSION['usr']. "</h1>   <table border='1' style='width:100%; border-collapse: collapse;'>
        <tr><th>ID</th><th>NOMBRE</th><th>PREU</th><th>IVA</th><th>QUANTITAT</th></tr>";
        foreach ($lines as $line) {
            $parts = explode(':', trim($line));
            $html.= "<tr><td>$parts[0]</td><td>$parts[1]</td><td>$parts[2]</td><td>$parts[3]</td><td>$parts[4]</td></tr>";
        }
        $html .= "</table>";
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Command.pdf",);

}

function changeQuantity($usr, $idProduct, $newQuantity) {
    $filename = "../cistelles/" . $usr . ".txt";

    if (file_exists($filename)) {

        $lines = readLines($filename);
        foreach ($lines as $i => $line) {
            $parts = explode(':', trim($line));
            if ($parts[0] === $idProduct) {
                $lines[$i] = "$idProduct:$parts[1]:$parts[2]:$parts[3]:$newQuantity";
                break;
            }
        }
        
        $file = fopen($filename, 'w');
        fwrite($file, implode(PHP_EOL, $lines));
        fclose($file);
    }
}

// SECTION 5 -------------------------------------------------


function showCommand() {
    $directory = '../comandes/';

    $files = scandir($directory);

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $filePath = $directory . $file;

            if (is_file($filePath)) {
                $fileNameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);

                echo "<h3>Procesando fichero: $fileNameWithoutExtension</h3>";

                viewCommand($fileNameWithoutExtension);

                echo "<hr>";
            }
        }
    }
}

function viewCommand($usr) {
    $filename = '../comandes/'. $usr .'.txt';
    if (file_exists($filename)){

        $lines = readlines($filename);
        $totalWithIVA = 0;
        $totalWithoutIVA = 0;
        $onlyIVA = 0;

        echo date("Y-m-d H:i:s");
        foreach ($lines as $line){
            if (!empty($line)) {
            $parts = explode(':', $line);
                $totalWithIVA +=($parts[2] + ($parts[2]*$parts[3])/100)*$parts[4];
                $totalWithoutIVA += $parts[2]*$parts[4];
                $onlyIVA += (($parts[2]*$parts[3])/100)*$parts[4];

                echo "<p> Product : " . $parts[1] . "</p>";
                echo "<p> Price: " . $parts[2] . "</p>";
                echo "<p> Iva : " . $parts[3] . "</p>";
                echo "<p> Quantity : " . $parts[4] . "</p>";  
                echo "<p> without iva : " . $parts[2]*$parts[3] . "</p>";
                echo "<p> only iva " . (($parts[2]*$parts[3])/100)*$parts[4];
                echo "<p> Preu with iva : " . ($parts[2] + ($parts[2]*$parts[3])/100)*$parts[4] . "</p>" . "\n";                
            }
        }
        }
    
    echo "<p> Total without iva: " . ($totalWithoutIVA) . "</p>";
    echo "<p> Total in iva: " . ($onlyIVA) . "</p>";

    echo "<p> Total with iva: " . ($totalWithIVA) . "</p>";

    echo "<form action='manager.php?filter=viewCommand' method='POST'>
                <input type='hidden' name='idClient' value='$usr'>
                <button  name='assign' value='acceptCommand'> Accept </button>
                <button  name='assign' value='finalizeCommand'> finalize </button>
                <button  name='assign' value='deleteCommand'> delete </button></form>";
}

?>