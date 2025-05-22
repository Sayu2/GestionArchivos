<?php
/*$dir = 'uploads/';

if (!is_dir($dir)) {
    echo "No hay archivos subidos.";
    return;
}

$files = scandir($dir);

foreach ($files as $file) {
    if ($file !== "." && $file !== "..") {
        echo "<a href='download.php?file=" . urlencode($file) . "' download>$file</a><br>";
        echo "<a href='deletefile.php?id_archivo=" . $row['id_archivos'] . "' onclick='return confirm(\"¿Estás seguro de eliminar este archivo?\")'>Eliminar</a>";

    }
}*/
?>
<?php
//session_start();
require_once "db.php";

/*if (!isset($_SESSION['id_usuario'])) {
  echo "Debes iniciar sesión.";
  exit;
}*/

//$id_usuario = $_SESSION['id_usuario'];
$id_usuario =1;

$sql = "SELECT id_archivos, nombre_archivo, ruta, fecha_subida, version 
        FROM archivos 
        WHERE subido_por = ?
        ORDER BY fecha_subida DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<div>";
    echo "<a href='" . $row['ruta'] . "' target='_blank'>" . htmlspecialchars($row['nombre_archivo']) . "</a> ";
    //echo "(Versión: " . $row['version'] . ", Fecha: " . $row['fecha_subida'] . ") ";
    echo "<a href='deletefile.php?id_archivo=" . $row['id_archivos'] . "' onclick='return confirm(\"¿Eliminar archivo?\")'>Eliminar</a>";
    echo "</div>";
  }
} else {
  echo "No has subido archivos.";
}
?>

