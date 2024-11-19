<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
include 'includes/db.php';

$success = false;
if (isset($_GET['success'])) {
    $success = true; // Mostrar mensaje de éxito si viene desde la redirección
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $director = $_POST['director'];
    $imagen = '';

    // Manejo de la imagen
    if (!empty($_FILES['imagen']['name'])) {
        $directorio = 'img/';
        $imagen = basename($_FILES['imagen']['name']);
        $ruta = $directorio . $imagen;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
            // Imagen subida correctamente
        } else {
            $error = "Error al subir la imagen.";
        }
    }

    // Insertar película en la base de datos
    $sql = "INSERT INTO peliculas (nombre, categoria, descripcion, fecha, director, imagen, usuario_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $nombre, $categoria, $descripcion, $fecha, $director, $imagen, $_SESSION['usuario_id']);

    if ($stmt->execute()) {
        $success = true;

        // Redirigir al usuario después del registro
        header("Location: register_movie.php?success=1");
        exit; // Importante: Detiene la ejecución después de la redirección
    } else {
        $error = "Error al registrar la película.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Película</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/register_movie.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-center flex-grow-1">Registrar Película</h1>
    </div>
    <form method="POST" enctype="multipart/form-data" class="mt-4">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <div class="mb-3">
        <label for="nombre" class="form-label"><i class="fa-solid fa-film"></i> Nombre de la Película</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="mb-3">
        <label for="categoria" class="form-label"><i class="fa-solid fa-film"></i> Categoría</label>
        <div class="custom-select">
    <select class="form-select" id="categoria" name="categoria" required>
        <option value="" disabled selected>Selecciona una categoría</option>
        <option value="Acción">Acción</option>
        <option value="Comedia">Comedia</option>
        <option value="Drama">Drama</option>
        <option value="Terror">Terror</option>
        <option value="Ciencia Ficción">Ciencia Ficción</option>
        <option value="Animación">Animación</option>
    </select>
</div>

    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label"><i class="fa-solid fa-film"></i> Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
    </div>
    <div class="mb-3">
        <label for="fecha" class="form-label"><i class="fa-solid fa-film"></i> Fecha de la película</label>
        <input type="date" class="form-control" id="fecha" name="fecha" required>
    </div>
    <div class="mb-3">
        <label for="director" class="form-label"><i class="fa-solid fa-film"></i> Director</label>
        <input type="text" class="form-control" id="director" name="director" required>
    </div>
    <div class="mb-3">
        <label for="imagen" class="form-label"><i class="fa-solid fa-film"></i> Imagen</label>
        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary btn-form">Registrar</button>
    <a href="dashboard.php" class="btn btn-secondary btn-form">Volver al menú</a>
</form>

</div>

<!-- Modal de Éxito -->
<?php if ($success): ?>
<div class="modal fade show" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" style="display: block;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Registro Exitoso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                La película se ha registrado exitosamente.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Mostrar el modal automáticamente
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
</script>
<?php endif; ?>
<?php include 'templates/footer.php'; ?>
</body>
</html>