<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Insertar vehículo</title>
<style>
  * {
    box-sizing: border-box;
  }

  body {
    font-family: Arial, sans-serif;
    background-color: #f3f4f6;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
  }

  h2 {
    color: #1e3a8a;
    margin-bottom: 20px;
  }
  form {
    background-color: #fff;
    padding: 30px 40px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 420px;
    text-align: left;
  }

  input[type="text"],
  select,
  input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
  }
  label {
    font-weight: bold;
    display: block;
    margin-top: 10px;
    margin-bottom: 5px;
  }
  .radio-group,
  .checkbox-group {
    margin-bottom: 10px;
  }

  .checkbox-group label {
    display: block; /* cada servicio en línea separada */
    font-weight: normal;
    margin-bottom: 5px;
  }

  input[type="radio"],
  input[type="checkbox"] {
    margin-right: 5px;
    transform: scale(1.1);
  }
  button {
    width: 100%;
    background-color: #1e3a8a;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 15px;
    cursor: pointer;
    margin-top: 15px;
    transition: background-color 0.2s ease-in-out;
  }

  button:hover {
    background-color: #2c52cc;
  }
  .success {
    color: green;
    font-weight: bold;
    margin-top: 15px;
  }

  .error {
    color: red;
    font-weight: bold;
  }

  ul.error {
    color: red;
    margin-top: 10px;
    padding-left: 20px;
  }

  a {
    display: inline-block;
    margin-top: 20px;
    color: #1e3a8a;
    text-decoration: none;
  }

  a:hover {
    text-decoration: underline;
    
    .botones {
  display: flex;
  justify-content: space-between;
  gap: 10px;
}

.btn-volver {
  flex: 1;
  text-align: center;
  background-color: #6b7280;
  color: white;
  border: none;
  padding: 12px;
  border-radius: 6px;
  font-size: 15px;
  text-decoration: none;
  line-height: 1;
  transition: background-color 0.2s ease-in-out;
}

.btn-volver:hover {
  background-color: #4b5563;
}
  }
</style>
</head>
<body>

<h2>Insertar Vehículo</h2>
<form action="" method="post" enctype="multipart/form-data">
  <input type="text" name="nombre" placeholder="Nombre del cliente" required><br>
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

  <div class="botones">
  <button type="submit" name="insertar">Guardar vehículo</button>
  <a href="index.html" class="btn-volver">Volver</a>
  </div>
  
</form>

<?php
if (isset($_POST["insertar"])) {   // Solo se ejecuta tras enviar el formulario
    try {
        $conexion = new PDO("mysql:host=localhost;dbname=infovehiculos", "root", "");
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Recoger y filtrar los datos
        $cliente   = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $marca     = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING);
        $matricula = filter_input(INPUT_POST, 'matricula', FILTER_SANITIZE_STRING);
        $tipo      = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
        $garantia  = filter_input(INPUT_POST, 'garantia', FILTER_VALIDATE_INT);

        // Recoger servicios adicionales (pueden ser varios)
        $servicios = isset($_POST['servicios']) && is_array($_POST['servicios'])
            ? implode(', ', array_map('strip_tags', $_POST['servicios']))
            : '';

        $errores = [];

        // Validaciones
        if (!$cliente){ $errores[] = "El nombre del cliente es obligatorio.";}
        if (!$marca){ $errores[] = "La marca es obligatoria.";}
        if (!$matricula){ $errores[] = "La matrícula es obligatoria.";}
        if (!$tipo){ $errores[] = "El tipo es obligatorio.";}
        if ($garantia === false){ $errores[] = "La garantía debe ser un número válido.";}

        // Procesar imagen
        $ruta = null;
        if (!empty($_FILES["imagen"]["name"])) {
            $foto = $_FILES["imagen"]["name"];
            $tmp_imagen = $_FILES["imagen"]["tmp_name"];
            $ruta = "uploads/" . uniqid() . "_" . basename($foto);

            if (!file_exists("uploads")){ mkdir("uploads", 0777, true);}

            if (!move_uploaded_file($tmp_imagen, $ruta)) {
                $errores[] = "Error al subir la imagen.";
            }
        }

        // Solo insertar si no hay errores
        if (empty($errores)) {
            $sql = "INSERT INTO tvehiculos (nombre, marca, matricula, tipo, garantia, servicios, imagen)
                    VALUES (:nombre, :marca, :matricula, :tipo, :garantia, :servicios, :imagen)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':nombre'    => $cliente,
                ':marca'     => $marca,
                ':matricula' => $matricula,
                ':tipo'      => $tipo,
                ':garantia'  => $garantia,
                ':servicios' => $servicios,
                ':imagen'    => $ruta
            ]);

            echo "<p style='color: green; font-weight: bold;'>✅ Registro insertado correctamente.</p>";
        } else {
            echo "<h3 style='color: red;'>Errores en el formulario:</h3><ul style='color: red;'>";

            foreach ($errores as $error) {
                echo "<li>" . htmlspecialchars($error) . "</li>";
            }

            echo "</ul>";
        }

    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error en la base de datos: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}








