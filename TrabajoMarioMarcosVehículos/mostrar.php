<?php
require "conexion.php";

$stmt = $pdo->query("SELECT * FROM tvehiculos");
$vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Listado de vehículos</title>
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
    margin: 25px 0;
    padding: 10px 20px;
    background-color: #0078D4;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: background-color 0.3s ease;
  }

  a:hover {
    background-color: #005ea3;
  }

  p {
    background: #fff;
    padding: 20px 30px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }
</style>
</head>
<body>
<h2>Listado de vehículos</h2>

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
</tr>
<?php foreach ($vehiculos as $a): ?>
<tr>
  <td><?= htmlspecialchars($a["id"]) ?></td>
  <td><?= htmlspecialchars($a["nombre"]) ?></td>
  <td><?= htmlspecialchars($a["marca"]) ?></td>
  <td><?= htmlspecialchars($a["matricula"]) ?></td>
  <td><?= htmlspecialchars($a["tipo"]) ?></td>
  <td><?= htmlspecialchars($a["garantia"]) ?></td>
  <td><?= htmlspecialchars($a["servicios"]) ?></td>
  <td>
    <?php if (!empty($a["imagen"])): ?>
      <img src="<?= htmlspecialchars($a["imagen"]) ?>" width="80">
    <?php else: ?>
      Sin foto
    <?php endif; ?>
  </td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<a href="index.html">Volver</a>
</body>
</html>

