<?php
require "conexion.php";

// --- Si se ha solicitado eliminar un vehículo ---
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener información del vehículo (para eliminar imagen si existe)
    $stmt = $pdo->prepare("SELECT imagen FROM tvehiculos WHERE id = ?");
    $stmt->execute([$id]);
    $vehiculo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vehiculo) {
        // Eliminar imagen física si existe
        if (!empty($vehiculo['imagen']) && file_exists($vehiculo['imagen'])) {
            unlink($vehiculo['imagen']);
        }

        // Eliminar registro de la base de datos
        $stmt = $pdo->prepare("DELETE FROM tvehiculos WHERE id = ?");
        $stmt->execute([$id]);

        $mensaje = "✅ Vehículo eliminado correctamente.";
    } else {
        $mensaje = "⚠️ Error: No se encontró el vehículo.";
    }
}

// --- Obtener lista de vehículos ---
$stmt = $pdo->query("SELECT * FROM tvehiculos");
$vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

  a {
    display: inline-block;
    padding: 6px 10px;
    color: white;
    background-color: #0078D4;
    border-radius: 6px;
    text-decoration: none;
    transition: background-color 0.3s;
  }

  a:hover {
    background-color: #005ea3;
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
// Confirmar antes de eliminar
function confirmarEliminacion(id) {
  if (confirm("¿Está seguro de que desea eliminar este vehículo?")) {
    window.location.href = "eliminar.php?id=" + id;
  }
}
</script>

</head>
<body>

<h2>Eliminar vehículo</h2>

<?php if (!empty($mensaje)): ?>
  <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
  <a href="eliminar.php">Volver a la lista</a>
  <a href="index.html">Inicio</a>

<?php elseif (empty($vehiculos)): ?>
  <p>No hay vehículos registrados.</p>
  <a href="index.html">Volver</a>

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

    <?php foreach ($vehiculos as $v): ?>
    <tr>
      <td><?= htmlspecialchars($v["id"]) ?></td>
      <td><?= htmlspecialchars($v["nombre"]) ?></td>
      <td><?= htmlspecialchars($v["marca"]) ?></td>
      <td><?= htmlspecialchars($v["matricula"]) ?></td>
      <td><?= htmlspecialchars($v["tipo"]) ?></td>
      <td><?= $v["garantia"] == 1 ? "Sí" : "No" ?></td>
      <td><?= htmlspecialchars($v["servicios"]) ?></td>
      <td>
        <?php if (!empty($v["imagen"])): ?>
          <img src="<?= htmlspecialchars($v["imagen"]) ?>" width="80">
        <?php else: ?>
          Sin foto
        <?php endif; ?>
      </td>
      <td>
        <a href="#" title="Eliminar vehículo" onclick="confirmarEliminacion(<?= $v['id'] ?>)">🗑️</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
  <a href="index.html">Volver</a>
<?php endif; ?>

</body>
</html>