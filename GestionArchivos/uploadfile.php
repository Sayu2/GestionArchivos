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
  <title>Módulo 4: Carga y Gestión de Archivos</title>
  <style>
    /* Estilos (idénticos a los que ya tenías)... */
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #e8f1fc;
      color: #333333;
    }

    h2, h3 {
      color: #0052a3;
      text-align: center;
    }

    button,
    input[type="submit"] {
      background-color: #0066cc;
      color: white;
      border: none;
      padding: 10px 20px;
      margin-top: 10px;
      cursor: pointer;
      border-radius: 5px;
      font-size: 16px;
    }

    button:hover {
      background-color: #004a99;
    }

    form {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      max-width: 700px;
      margin: 30px auto;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    input[type="file"] {
  display: block;
  margin-top: 10px;
  padding: 10px;
  background-color: #ffffff;
  border: 2px solid #0052a3;
  border-radius: 8px;
  color: #0052a3;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
  max-width: 100%;
}

input[type="file"]::file-selector-button {
  background-color: #0066cc;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

input[type="file"]::file-selector-button:hover {
  background-color: #004a99;
}

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
    }

    #fileList {
      background-color: #ffffff;
      border: 1px solid #ccc;
      padding: 15px;
      margin: 20px auto;
      max-width: 1000px;
      border-radius: 5px;
    }

    a {
      color: #0066cc;
      text-decoration: none;
      display: block;
      text-align: center;
      margin-top: 20px;
    } 

    a:hover {
      text-decoration: underline;
      
    }
    p{
      text-align: center;
      
    }

    .user-info {
      text-align: center;
      margin-top: 10px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <a href="historial.php" style="display:inline-block; text-align: right; margin-top:20px; color:#0066cc; text-decoration:none;">Historial de versiones -></a>


  <h2>Módulo 4: Carga y Gestión de Archivos</h2>

 <!-- <div class="user-info">
    Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong> |
    <a href="logout.php">Cerrar sesión</a>
  </div>-->

<form action="upload.php" id="uploadForm" method="POST" enctype="multipart/form-data">
  <label>Seleccionar archivo:</label>
  <input type="file" name="archivo" required>
  <button type="submit">Subir</button>
</form>

<div id="uploadStatus"></div>

<h3>Archivos Subidos:</h3>
<div id="fileList">Cargando archivos...</div>

<!--<a href="historial.php">Ver historial de versiones</a>-->

<script>
 document.getElementById("uploadForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch("upload.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(response => {
      document.getElementById("uploadStatus").innerHTML = 
        `<p style="color: green;">Archivo subido correctamente. Recargando...</p>`;

      // Recarga la página después de 1.5 segundos
      setTimeout(() => {
        location.reload();
      }, 1500);
    })
    .catch(error => {
      document.getElementById("uploadStatus").innerHTML = 
        `<p style="color: red;">Error al subir el archivo.</p>`;
      console.error("Error:", error);
    });
  });

  function actualizarLista() {
    fetch("list_files.php")
      .then(res => res.text())
      .then(data => document.getElementById("fileList").innerHTML = data);
  }

  actualizarLista();
</script>
<script>document.addEventListener("click", function(e) {
  if (e.target && e.target.classList.contains("eliminar-btn")) {
    const id = e.target.getAttribute("data-id");
    if (confirm("¿Estás seguro de eliminar este archivo?")) {
      fetch(`eliminar_archivo.php?id_archivo=${id}`)
        .then(res => res.text())
        .then(response => {
          alert(response);
          actualizarLista(); // Recargar solo la lista de archivos
        })
        .catch(err => {
          alert("Error al eliminar el archivo.");
          console.error(err);
        });
    }
  }
});
</script>
 <!-- <script>
    fetch('list_files.php')
      .then(res => res.text())
      .then(data => document.getElementById('fileList').innerHTML = data);
  </script>-->
</body>
</html>
