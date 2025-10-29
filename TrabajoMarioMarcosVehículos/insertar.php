<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Insertar vehículo</title>
<style>
  body { font-family: Arial; background: #f3f4f6; text-align: center; }
  form { background: white; padding: 30px; border-radius: 10px; display: inline-block; }
  input, select { margin: 5px; padding: 8px; width: 90%; }
  button { background: #1e3a8a; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
</style>
</head>
<body>

<h2>Insertar Vehículo</h2>
<form action="" method="post" enctype="multipart/form-data">
  <input type="text" name="cliente" placeholder="Nombre del cliente" required><br>
  <select name="marca" required>
    <option value="">Seleccione marca</option>
    <option>Toyota</option>
    <option>Ford</option>
    <option>Renault</option>
    <option>Mazda</option>
    <option>Citroen</option>
    <option>Volvo</option>
    <option>Audi</option>
    <option>Mercedes</option>
    <option>Chebrolet</option>
    <option>Aston Martin</option>
    <option>Ferrai</option>
    <option>Porche</option>
    <option>Peugeot</option>
    <option>Hyundai</option>
    <!-- no se nos ocuuren mas marcas, se pueden añadir mas-->
    
    
    
  </select><br>
  <input type="text" name="matricula" placeholder="Matrícula" required><br>
  <input type="text" name="tipo" placeholder="Tipo" required><br>
  

  <label>¿En garantía?</label><br>
  <input type="radio" name="garantia" value="1" required> Sí
  <input type="radio" name="garantia" value="0" required> No<br>

  <label>Servicios adicionales:</label><br>
  <input type="checkbox" name="servicios[]" value="Cambio de aceite">Cambio de aceite
  <input type="checkbox" name="servicios[]" value="Revisión">Revisión
  <input type="checkbox" name="servicios[]" value="ITV">ITV<br>

  <label>Imagen:</label><br>
  <input type="file" name="imagen"><br><br>

  <button type="submit" name="insertar">Guardar vehículo</button>
</form>

<?php

try {
    $conexion = new PDO("mysql:host=localhost;dbname=bd_daw2", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $cliente = filter_input(INPUT_POST, 'cliente', FILTER_SANITIZE_STRING);
    $marca = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING);
    $matricula = filter_input(INPUT_POST, 'matricula', FILTER_SANITIZE_STRING);
    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
    $garantia = filter_input(INPUT_POST, 'garantia', FILTER_VALIDATE_INT);
// Procesar imagen
    $foto = $_FILES["foto"]["name"];
    $tmp_imagen = $_FILES["foto"]["tmp_name"];
    $ruta = "uploads/" . uniqid() . "_" . $foto;
    
    $errores = [];

    if (!$nombre) {$errores[] = "El nombre de usuario es obligatorio.";}
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {$errores[] = "El email no es válido.";}
    if (!$curso) {$errores[] = "El curso no es válido.";}
    if (!$turno) {$errores[] = "Debes marcar un turno.";}

    // Manejo de la imagen
    $ruta = null;
    if (!empty($_FILES["foto"]["name"])) {
        $foto = $_FILES["foto"]["name"];
        $tmp_imagen = $_FILES["foto"]["tmp_name"];
        $ruta = "uploads/" . uniqid() . "_" . basename($foto);

        // Crear carpeta si no existe
        if (!file_exists("uploads")) {
            mkdir("uploads", 0777, true);
        }

        if (!move_uploaded_file($tmp_imagen, $ruta)) {
            $errores[] = "Error al subir la imagen.";
        }
    } else {
        $errores[] = "No se seleccionó ninguna imagen.";
    }

    if (empty($errores)) {
        $sql = "INSERT INTO talumnos (nombre, email, curso, turno, foto)
                VALUES (:nombre, :email, :curso, :turno, :foto)";
        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':curso' => $curso,
            ':turno' => $turno,
            ':foto' => $ruta
        ]);

        echo "<p class='success'>Alumno insertado correctamente</p>";
    } else {
        echo "<h2 class='error'>Errores en el formulario:</h2><ul class='error'>";
        foreach ($errores as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
    }

    echo "<a href='MarcosAcedo_PracticaPDO.html'>Volver al formulario</a>";

} catch (PDOException $e) {
    echo "<p class='error'>Error en la base de datos: " . htmlspecialchars($e->getMessage()) . "</p>";
}





if (isset($_POST['insertar'])) {
    include 'conexion.php';

    // ✅ Usamos filter_input() en lugar de $_POST[]
    $cliente = filter_input(INPUT_POST, 'cliente', FILTER_SANITIZE_STRING);
    $marca = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING);
    $matricula = filter_input(INPUT_POST, 'matricula', FILTER_SANITIZE_STRING);
    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
    $garantia = filter_input(INPUT_POST, 'garantia', FILTER_VALIDATE_INT);

    // Checkbox múltiple — se trata aparte
    $servicios = "";
    if (!empty($_POST['servicios']) && is_array($_POST['servicios'])) {
        $servicios = implode(", ", array_map('htmlspecialchars', $_POST['servicios']));
    }

    // Subida de imagen (sin acceso directo inseguro)
    $ruta = "";
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $carpeta = "uploads/";
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);
        $nombreArchivo = basename($_FILES["imagen"]["name"]);
        $ruta = $carpeta . $nombreArchivo;
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
    }

    // Inserción con PDO y parámetros preparados
    $stmt = $conexion->prepare("
        INSERT INTO vehiculos (cliente, marca, matricula, tipo, garantia, servicios, imagen)
        VALUES (:cliente, :marca, :matricula, :tipo, :garantia, :servicios, :imagen)
    ");

    $stmt->execute([
        ':cliente' => $cliente,
        ':marca' => $marca,
        ':matricula' => $matricula,
        ':tipo' => $tipo,
        ':garantia' => $garantia,
        ':servicios' => $servicios,
        ':imagen' => $ruta
    ]);

    echo "<p>✅ Vehículo insertado correctamente.</p>";
   if ($ruta) {
        echo "<img src='$ruta' width='200'><br>";
    }
    echo "<a href='insertar.php'>Volver al formulario</a> | <a href='index.html'>Menú principal</a>";
}


