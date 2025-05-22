<?php
/*session_start();


// Verificar sesión activa
if (!isset($_SESSION['id_usuario'])) {
    echo "Acceso no autorizado.";
    exit;
}
$_SESSION['id_usuario']*/
require_once "db.php";
$id_usuario = 1;

// Verificar que se recibió el ID del archivo por GET
if (isset($_GET['id_archivo'])) {
    $id_archivo = intval($_GET['id_archivo']);

    // 1. Obtener la ruta del archivo desde la base de datos
    $stmt = $conn->prepare("SELECT ruta FROM archivos WHERE id_archivos = ? AND subido_por = ?");
    $stmt->bind_param("ii", $id_archivo, $id_usuario);
    $stmt->execute();
    $stmt->bind_result($ruta_archivo);
    $stmt->fetch();
    $stmt->close();

    // 2. Verificar si existe el archivo físicamente y eliminarlo
    if ($ruta_archivo && file_exists($ruta_archivo)) {
        if (unlink($ruta_archivo)) {
            // 3. Eliminar el registro de la base de datos
            $stmt = $conn->prepare("DELETE FROM archivos WHERE id_archivos = ? AND subido_por = ?");
            $stmt->bind_param("ii", $id_archivo, $id_usuario);
            $stmt->execute();
            $stmt->close();

            echo "Archivo eliminado correctamente.";
            header("Location: uploadfile.php");
            exit();

        } else {
            echo "No se pudo eliminar el archivo del servidor.";
        }
    } else {
        echo "Archivo no encontrado o no tienes permiso para eliminarlo.";
    }
} else {
    echo "ID de archivo no especificado.";
}
?>
