<?php
/*session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
  header("Location: login.php"); // Redirige al login si no está autenticado
  exit();
}*/
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Archivos</title>
  <style>
    body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #e8f1fc;
  margin: 0;
  padding: 20px;
  color: #333333;
}

h2 {
  color: #0052a3;
  text-align: center;
  margin-bottom: 30px;
   text-align: center;
}

h4 {
    text-align: center;
  color: #0066cc;
  margin-top: 30px;
  border-bottom: 2px solid #0052a3;
  padding-bottom: 5px;
}

ul {
  list-style-type: none;
  padding-left: 0;
  max-width: 800px;
  margin: 0 auto 30px;
  background-color: #ffffff;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

li {
  padding: 12px 20px;
  border-bottom: 1px solid #eee;
  font-size: 15px;
}

li:last-child {
  border-bottom: none;
}

a {
  color: #0066cc;
  text-decoration: none;
  font-weight: bold;
}

a:hover {
  text-decoration: underline;
}

  </style>
</head>
<body>
 <?php
include 'db.php';

$id_proyecto = 2; // o pásalo por GET
$id_tarea = 2;

$sql = "SELECT nombre_archivo, MAX(version) as ultima_version 
        FROM archivos 
        WHERE id_proyecto = ? AND id_tarea = ?
        GROUP BY nombre_archivo";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_proyecto, $id_tarea);
$stmt->execute();
$result = $stmt->get_result();
echo "<a href='uploadfile.php' style='display:inline-block; margin-top:20px; color:#0066cc; text-decoration:none;'>← Regresar a la carga de archivos</a> ";
echo "<h2>Historial de archivos por versión</h2>";

while ($row = $result->fetch_assoc()) {
    $nombre = $row['nombre_archivo'];

    echo "<h4>$nombre</h4>";
    echo "<ul>";

    // Obtener todas las versiones de este archivo
    $stmt2 = $conn->prepare("SELECT * FROM archivos 
                             WHERE nombre_archivo = ? 
                             AND id_proyecto = ? AND id_tarea = ?
                             ORDER BY version DESC");

    $stmt2->bind_param("sii", $nombre, $id_proyecto, $id_tarea);
    $stmt2->execute();
    $res2 = $stmt2->get_result();

    while ($archivo = $res2->fetch_assoc()) {
        echo "<li>Versión {$archivo['version']} - 
              Subido por: {$archivo['subido_por']} - 
              Fecha: {$archivo['fecha_subida']} - 
              <a href='{$archivo['ruta']}' download>Descargar</a></li>";
    }

    echo "</ul>";
    $stmt2->close();
}

$stmt->close();
$conn->close();
?>
</body>
</html>
