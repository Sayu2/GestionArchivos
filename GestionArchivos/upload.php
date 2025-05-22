<?php
include 'db.php';

$uploadDir = 'uploads/';

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES["archivo"]) && $_FILES["archivo"]["error"] === 0) {
    $fileName = basename($_FILES["archivo"]["name"]);
    $filePath = $uploadDir . $fileName;

    // Control de versiones
    $info = pathinfo($fileName);
    $base = $info['filename'];
    $ext = isset($info['extension']) ? "." . $info['extension'] : "";
    $version = 1;

    while (file_exists($filePath)) {
        $fileName = "$base-v$version$ext";
        $filePath = $uploadDir . $fileName;
        $version++;
    }

    if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $filePath)) {
        // Datos simulados para ejemplo
        $nombre_archivo = $info['filename'];
        $ruta = $uploadDir.$nombre_archivo.$ext;
        $id_proyecto = 2; // deberías pasarlo por POST o sesión
        $id_tarea = 2;
        $subido_por = 1; // puedes tomarlo de $_SESSION
        $fecha = date("Y-m-d H:i:s");

        // Inserta en base de datos
       $stmt = $conn->prepare("INSERT INTO archivos (id_proyecto, id_tarea, nombre_archivo, ruta, fecha_subida, version, subido_por)
        VALUES (?, ?, ?, ?, NOW(), ?, ?)");

        $stmt->bind_param("iissii", $id_proyecto, $id_tarea, $nombre_archivo, $ruta, $version, $subido_por);


        if ($stmt->execute()) {
            echo "Archivo subido y registrado con éxito.";
        } else {
            echo "Error al guardar en la base de datos: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error al subir archivo.";
    }
} else {
    echo "Archivo inválido.";
}

$conn->close();
?>
