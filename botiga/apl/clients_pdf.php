<?php
require_once 'vendor/autoload.php';
use Dompdf\Dompdf;

$filename = "user.txt";

if (file_exists($filename)) {
    $file = fopen($filename, "r");
    $content = "<h1>User List</h1><hr>";
    
    while (($line = fgets($file)) !== false) {
        $parts = explode(':', trim($line));
        $content .= "<p>Username: $parts[0]</p>";
        $content .= "<p>Email: $parts[3]</p>";
        $content .= "<p>Role: $parts[2]</p>";
        $content .= "<hr>";
    }
    fclose($file);

    $dompdf = new Dompdf();
    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("user_list.pdf");
} else {
    echo "File not found.";
}
?>