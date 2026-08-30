<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$sql = "SELECT * FROM usuarios_sistema ORDER BY id_usuario ASC";

$resultado = $conexion->query($sql);

$usuarios = $resultado->fetch_all(MYSQLI_ASSOC);

$resultado->free();
$conexion->close();

pagina('cliente/pages/admin/usuarios/usuarios.php', ['usuarios' => $usuarios]);