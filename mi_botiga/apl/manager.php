<?php

require('functions.php');
require('../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

session_start();

if (isset($_GET['filter']) && $_GET['filter'] === 'logout') {
    logout();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reportClient') {
    // Validar campos obligatorios
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
        } else {
            echo 'No se pudo enviar el correo. Inténtalo nuevamente.';
        }
    } catch (Exception $e) {
        echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
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

<?php endif; ?>

</body>
</html>
