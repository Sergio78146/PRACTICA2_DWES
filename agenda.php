<?php
session_start();

if (!isset($_SESSION['agenda'])) {
    $_SESSION['agenda'] = [];
}

// Procesamiento de formulario de contacto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    if (empty($nombre)) {
        $mensaje = "El nombre es obligatorio.";
    } else {
        if ($telefono) {
            $_SESSION['agenda'][$nombre] = $telefono;
            $mensaje = "Contacto agregado o actualizado.";
        } elseif (isset($_SESSION['agenda'][$nombre])) {
            unset($_SESSION['agenda'][$nombre]);
            $mensaje = "Contacto eliminado.";
        }
    }
}

// Procesamiento para vaciar la agenda
if (isset($_GET['vaciar']) && $_GET['vaciar'] == '1') {
    $_SESSION['agenda'] = [];
    $mensaje = "Agenda vaciada.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda de Contactos</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .agenda, .formulario, .vaciar { margin: 10px; padding: 10px; border: 1px solid #ccc; }
        .mensaje { color: red; }
    </style>
</head>
<body>

<h1>Agenda de Contactos</h1>

<!-- Mostrar contactos -->
<div class="agenda">
    <h2>Contactos</h2>
    <?php if (!empty($_SESSION['agenda'])): ?>
        <ul>
            <?php foreach ($_SESSION['agenda'] as $nombre => $telefono): ?>
                <li><?php echo htmlspecialchars($nombre) . ': ' . htmlspecialchars($telefono); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No hay contactos en la agenda.</p>
    <?php endif; ?>
</div>

<!-- Formulario de contacto -->
<div class="formulario">
    <h2>Agregar o Editar Contacto</h2>
    <?php if (!empty($mensaje)): ?>
        <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="post" action="agenda.php">
        <label>Nombre: <input type="text" name="nombre" required></label>
        <label>Teléfono: <input type="text" name="telefono"></label>
        <button type="submit">Añadir Contacto</button>
        <button type="reset">Limpiar Campos</button>
    </form>
</div>

<!-- Botón para vaciar agenda -->
<?php if (!empty($_SESSION['agenda'])): ?>
    <div class="vaciar">
        <form method="get" action="agenda.php">
            <button type="submit" name="vaciar" value="1">Vaciar Agenda</button>
        </form>
    </div>
<?php endif; ?>

</body>
</html>
