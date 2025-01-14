<?php

function readLines($filename){
    if (file_exists($filename)){
        $size = filesize($filename);
        $file = fopen($filename, 'r');
        $lines = explode(PHP_EOL, fread($file, $size));
        fclose($file);
        return $lines;
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

function showUserInfo() {
    if (!isset($_SESSION['usr'])) {
        echo "You must be logged in to view your information.";
        return;
    }

    $username = $_SESSION['usr'];
    $lines = readLines('users.txt');

    foreach ($lines as $line) {
        $parts = explode(':', trim($line));
    
        if ($parts[0] === $username) {
            echo "<h2>User Information</h2>";
            echo "<p><strong>Username:</strong> {$parts[0]}</p>";
            echo "<p><strong>Email:</strong> {$parts[3]}</p>";
            echo "<p><strong>ID:</strong> {$parts[4]}</p>";
            echo "<p><strong>First Name:</strong> {$parts[5]}</p>";
            echo "<p><strong>Last Name:</strong> {$parts[6]}</p>";
            
            if (isset($parts[7])) {
                echo "<p><strong>Phone:</strong> {$parts[7]}</p>";
            }
            return;
        }
    }

    echo "No information found for the user.";
}




?>