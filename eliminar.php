<?php
try {
    $conexion = new PDO("mysql:host=localhost;dbname=infovehiculos", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener lista de vehículos para mostrar en el select
    $stmt = $conexion->query("SELECT id, nombre, marca, matricula FROM tvehiculos ORDER BY id ASC");
    $vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $mensaje = "";

    // Si se envía el formulario
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($id) {
            $sql = "DELETE FROM tvehiculos WHERE id = :id";
            $stmt = $conexion->prepare($sql);

            if ($stmt->execute([':id' => $id])) {
                $mensaje = "✅ Vehículo eliminado correctamente.";
            } else {
                $mensaje = "⚠️ Error al eliminar el vehículo.";
            }
        } else {
            $mensaje = "Por favor selecciona un vehículo válido.";
        }
    }

} catch (PDOException $e) {
    $mensaje = "Error en la base de datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Eliminar vehículo</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
  }

  h2 {
    color: #333;
  }

  form {
    background: #fff;
    padding: 25px 40px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
  }

  select, button {
    padding: 10px;
    font-size: 1rem;
    border-radius: 6px;
    border: 1px solid #ccc;
    margin: 10px 0;
    width: 100%;
    max-width: 300px;
  }

  button {
    background-color: #d9534f;
    color: white;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s;
  }

  button:hover {
    background-color: #c9302c;
  }

  .mensaje {
    margin-top: 20px;
    padding: 12px;
    border-radius: 6px;
    width: 100%;
    max-width: 400px;
    text-align: center;
  }

  .ok {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }

  .error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }

  a {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    background-color: #0078D4;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
  }

  a:hover {
    background-color: #005ea3;
  }
</style>
</head>
<body>

<h2>Eliminar vehículo</h2>

<form method="post" action="">
  <label for="id">Selecciona el vehículo a eliminar:</label><br>
  <select name="id" id="id" required>
    <option value="">-- Selecciona un vehículo --</option>
    <?php foreach ($vehiculos as $v): ?>
      <option value="<?= htmlspecialchars($v['id']) ?>">
        <?= htmlspecialchars($v['id']) ?> - <?= htmlspecialchars($v['nombre']) ?> (<?= htmlspecialchars($v['marca']) ?> - <?= htmlspecialchars($v['matricula']) ?>)
      </option>
    <?php endforeach; ?>
  </select><br>

  <button type="submit">Eliminar</button>
</form>

<?php if (!empty($mensaje)): ?>
  <div class="mensaje <?= strpos($mensaje, '✅') !== false ? 'ok' : 'error' ?>">
    <?= $mensaje ?>
  </div>
<?php endif; ?>

<a href="mostrar.php">Volver al listado</a>

</body>
</html>
