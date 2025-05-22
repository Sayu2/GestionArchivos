<?php
if (!isset($_GET['file'])) {
    die("Parámetro de archivo no especificado.");
}

$file = basename($_GET['file']); // Sanitiza el nombre del archivo
$filepath = "uploads/" . $file;

if (file_exists($filepath)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename=\"$file\"");
    readfile($filepath);
    exit;
} else {
    echo "El archivo no existe.";
}
?>
