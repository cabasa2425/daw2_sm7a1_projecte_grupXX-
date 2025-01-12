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
        echo "<script> body.innerHTML = 'File not found'</script>";
        return;
    }
}

function login($name, $password){

    $lines = readLines('users.txt');
    foreach ($lines as $line){
        $parts = explode(":", $line);
            if ($parts[0] === $name){

            if (password_verify($password, $parts[1])){
                session_start();
                $_SESSION['usr'] = $name;
                $_SESSION['type'] = $parts[2]; 
                return true; 
            } else {
                return false;
            }
        }
    }
}


function registerManager($usr,$pwd,$type,$email,$id,$name,$surname) {

    $filename = 'users.txt';
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
    
    $filename = 'users.txt';
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
function logout(){
    session_start();
    session_destroy();
    header('Location: index.php');
}

function showManagers(){

            $lines = readLines('users.txt');
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
    
        $lines = readLines('users.txt');
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
    
    $lines = readLines('users.txt');
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
    $lines = readLines('users.txt');
    $newLines = [];
    foreach ($lines as $line){
        $parts = explode(':', trim($line));
        if ($parts[4]!== $id) {
            $newLines[] = $line;
        } else {
            $newLines[] = "$usr:$parts[1]:$parts[2]:$email:$parts[4]:$name:$surname";
        }
    }
    $file = fopen('users.txt', 'w');
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
    $lines = readLines('users.txt');
    $newLines = [];
    foreach ($lines as $line){
        $parts = explode(':', trim($line));
        if ($parts[4]!== $id) {
            $newLines[] = $line;
        } else {
            $newLines[] = "$usr:$parts[1]:$parts[2]:$email:$parts[4]:$name:$surname:$phone";
        }
    }
    $file = fopen('users.txt', 'w');
    fwrite($file, implode(PHP_EOL, $newLines));
    array_pop($lines);
}




function deleteManager(){
    $id = $_POST['id'];
    $lines = readLines('users.txt');
    $newLines = [];
    foreach ($lines as $line){
        $parts = explode(':', trim($line));
        if ($parts[4]!== $id) {
            $newLines[] = $line;
        }
    }
    $file = fopen('users.txt', 'w');
    fwrite($file, implode(PHP_EOL, $newLines));
    array_pop($lines);
}

function checkUser() {
    if (!$_SESSION['usr']) {
        header('Location: index.php');
        exit();
    }
    return true;
}

function checkAdmin() {
    if (!isset($_SESSION['usr']) || $_SESSION['type'] !== 'admin') {
        return false;
    } else if (!isset($_SESSION['usr'])) {
        header('Location: index.php');
        exit();
    }
    return true;
}

function updateAdminInformation() {
    $usr = $_POST['usr'];
    $pwd = $_POST['pwd'];
    $email = $_POST['email'];

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    $lines = readLines('users.txt');
    foreach ($lines as $i => $line) {
        $parts = explode(':', trim($line));
        if ($parts[2] === 'admin') {
            $lines[$i] = "$usr:$hashedPwd:$parts[2]:$email";
            break;
        }
    }
    $file = fopen('users.txt', 'w');
    fwrite($file, implode(PHP_EOL, $lines));
    header('Location: index.php');
    exit();
}

function ManagerToPDF() {
    require_once('../vendor/autoload.php');
    $dompdf = new Dompdf\Dompdf();
    $lines = readLines('users.txt');
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
    $lines = readLines('users.txt');
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
    $lines = readLines('users.txt');
    $newLines = [];
    foreach ($lines as $line) {
        $parts = explode(':', trim($line));
        if ($parts[4]!== $id) {
            $newLines[] = $line;
        }
    }
    $file = fopen('users.txt', 'w');
    fwrite($file, implode(PHP_EOL, $newLines));
}

function updateManagerInformations() {
    $usr = $_POST['usr'];
    $pwd = $_POST['pwd'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $id = $_POST['id'];

    $lines = readLines('users.txt');
    foreach ($lines as $i => $line) {
        $parts = explode(':', trim($line));
        if ($parts[4] !== $id) {
            $lines[$i] = "$usr:$pwd:$parts[2]:$email:$parts[4]:$name:$surname";
            break;
        }
    }
    $file = fopen('users.txt', 'w');
    fwrite($file, implode("\n", $lines));
    fclose($file);
}

// SECTION 3 ----------------------------------------


require('../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function sendEmail($user_id, $request, $report){




    $user_id = $_POST['id'];
    $request = $_POST['request'];
    $report = $_POST['report'];

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

function showProducts(){
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

?>