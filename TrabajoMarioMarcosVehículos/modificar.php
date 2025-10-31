<?php
require "conexion.php";

// Si se ha enviado el formulario para actualizar
if (isset($_POST['guardar'])) {
    $id = $_POST['id'];
    $matricula = filter_input(INPUT_POST, 'matricula', FILTER_SANITIZE_STRING);
    $garantia = filter_input(INPUT_POST, 'garantia', FILTER_VALIDATE_INT);

    if ($matricula && $garantia !== false) {
        $stmt = $pdo->prepare("UPDATE tvehiculos SET matricula = ?, garantia = ? WHERE id = ?");
        $stmt->execute([$matricula, $garantia, $id]);
        $mensaje = "✅ Vehículo actualizado correctamente.";
    } else {
        $mensaje = "⚠️ Error: Datos no válidos.";
    }
}

// Si se selecciona un vehículo para modificar
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM tvehiculos WHERE id = ?");
    $stmt->execute([$id]);
    $vehiculo = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtener la lista de vehículos
$stmt = $pdo->query("SELECT * FROM tvehiculos");
$vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Modificar vehículo</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  h2 {
    color: #333;
    margin-top: 40px;
    margin-bottom: 20px;
  }

  table {
    border-collapse: collapse;
    width: 90%;
    max-width: 1000px;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 10px;
    overflow: hidden;
  }

  th, td {
    text-align: center;
    padding: 12px;
  }

  th {
    background-color: #0078D4;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  tr:nth-child(even) {
    background-color: #f9fafb;
  }

  tr:hover {
    background-color: #eef5ff;
  }

  img {
    border-radius: 6px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
  }

  a, button {
    display: inline-block;
    margin: 10px 0;
    padding: 8px 15px;
    background-color: #0078D4;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: background-color 0.3s ease;
    border: none;
    cursor: pointer;
  }

  a:hover, button:hover {
    background-color: #005ea3;
  }

  form {
    background: #fff;
    padding: 25px 40px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    margin-top: 20px;
  }

  input[type="text"], input[type="number"] {
    width: 100%;
    padding: 10px;
    margin: 8px 0 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
  }

  .mensaje {
    background: #fff;
    padding: 20px 30px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    margin-top: 20px;
  }
</style>

<script>
function confirmarGuardado() {
  return confirm("¿Está seguro de que quiere guardar los cambios?");
}
</script>

</head>
<body>

<h2>Modificar vehículo</h2>

<?php if (!empty($mensaje)): ?>
  <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
  <a href="modificar.php">Volver a la lista</a>

<?php elseif (isset($vehiculo)): ?>
  <form method="post" onsubmit="return confirmarGuardado()">
    <input type="hidden" name="id" value="<?= htmlspecialchars($vehiculo['id']) ?>">

    <label>Matrícula:</label>
    <input type="text" name="matricula" value="<?= htmlspecialchars($vehiculo['matricula']) ?>" required>

    <label>Garantía:</label>
    <div style="display: flex; gap: 15px; margin-bottom: 15px;">
        <label>
        <input type="radio" name="garantia" value="1" <?= $vehiculo['garantia'] == 1 ? 'checked' : '' ?>> Sí
        </label>
        <label>
        <input type="radio" name="garantia" value="0" <?= $vehiculo['garantia'] == 0 ? 'checked' : '' ?>> No
        </label>
    </div>  
    <div style="display: flex; gap: 10px;">
      <button type="submit" name="guardar">Guardar cambios</button>
      <a href="modificar.php">Cancelar</a>
    </div>
  </form>

<?php else: ?>

<?php if (empty($vehiculos)): ?>
  <p>No hay vehículos registrados.</p>
<?php else: ?>
  <table>
    <tr>
      <th>Id</th>
      <th>Nombre</th>
      <th>Marca</th>
      <th>Matrícula</th>
      <th>Tipo</th>
      <th>Garantía</th>
      <th>Servicios</th>
      <th>Imagen</th>
      <th>Acción</th>
    </tr>
    <?php foreach ($vehiculos as $a): ?>
    <tr>
      <td><?= htmlspecialchars($a["id"]) ?></td>
      <td><?= htmlspecialchars($a["nombre"]) ?></td>
      <td><?= htmlspecialchars($a["marca"]) ?></td>
      <td><?= htmlspecialchars($a["matricula"]) ?></td>
      <td><?= htmlspecialchars($a["tipo"]) ?></td>

      <!-- ✅ Aquí cambiamos la garantía para mostrar “Sí” o “No” -->
      <td><?= $a["garantia"] == 1 ? "Sí" : "No" ?></td>

      <td><?= htmlspecialchars($a["servicios"]) ?></td>
      <td>
        <?php if (!empty($a["imagen"])): ?>
          <img src="<?= htmlspecialchars($a["imagen"]) ?>" width="80">
        <?php else: ?>
          Sin foto
        <?php endif; ?>
      </td>
      <td>
        <a href="modificar.php?id=<?= $a['id'] ?>" title="Modificar vehículo">✏️</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<a href="index.html">Volver</a>
<?php endif; ?>

</body>
</html>