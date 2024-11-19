<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
include 'includes/db.php';

// Verificar la opción de ordenamiento seleccionada
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'nombre'; // Default: ordenar por nombre
$order_dir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'ASC'; // Default: ASC
$category_filter = isset($_GET['categoria']) ? $_GET['categoria'] : ''; // Filtro de categoría

// Consultar películas con ordenamiento y filtro de categoría
$sql = "SELECT * FROM peliculas WHERE usuario_id = ?";

if ($category_filter != '') {
    $sql .= " AND categoria = ?";
}

$sql .= " ORDER BY $order_by $order_dir";
$stmt = $conn->prepare($sql);

// Vincular parámetros
if ($category_filter != '') {
    $stmt->bind_param("is", $_SESSION['usuario_id'], $category_filter);
} else {
    $stmt->bind_param("i", $_SESSION['usuario_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $stmt_delete = $conn->prepare("DELETE FROM peliculas WHERE id = ? AND usuario_id = ?");
    $stmt_delete->bind_param("ii", $delete_id, $_SESSION['usuario_id']);
    if ($stmt_delete->execute()) {
        echo "<script>alert('Película eliminada exitosamente.');</script>";
        echo "<script>window.location.href='list_movies.php';</script>"; // Recarga la página
    } else {
        echo "<script>alert('Error al eliminar la película.');</script>";
    }
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Películas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/list-movies.css"> <!-- Archivo CSS mejorado -->
</head>
<body>
<a href="dashboard.php" class="btn btn-secondary btn-form">Volver al menú</a>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-center flex-grow-1">Lista de Películas</h1>
    </div>

    <!-- Filtros de ordenación y categoría -->
    <div class="mb-3">
        <form action="" method="GET">
        <label for="order_by" class="form-label">Ordenar por:</label>
            <select name="order_by" id="order_by" class="form-select" onchange="this.form.submit()">
                <option value="nombre" <?php echo $order_by == 'nombre' ? 'selected' : ''; ?>>Nombre</option>
                <option value="director" <?php echo $order_by == 'director' ? 'selected' : ''; ?>>Director</option>
            </select>

            <label for="order_dir" class="form-label mt-2">Orden:</label>
            <select name="order_dir" id="order_dir" class="form-select" onchange="this.form.submit()">
                <option value="ASC" <?php echo $order_dir == 'ASC' ? 'selected' : ''; ?>>A-Z</option>
                <option value="DESC" <?php echo $order_dir == 'DESC' ? 'selected' : ''; ?>>Z-A</option>
            </select>

            <label for="categoria" class="form-label mt-2">Filtrar por Categoría:</label>
            <select name="categoria" id="categoria" class="form-select" onchange="this.form.submit()">
                <option value="">Todas</option>
                <option value="Acción" <?php echo $category_filter == 'Acción' ? 'selected' : ''; ?>>Acción</option>
                <option value="Comedia" <?php echo $category_filter == 'Comedia' ? 'selected' : ''; ?>>Comedia</option>
                <option value="Ciencia Ficción" <?php echo $category_filter == 'Ciencia Ficción' ? 'selected' : ''; ?>>Ciencia Ficción</option>
                <option value="Animación" <?php echo $category_filter == 'Animación' ? 'selected' : ''; ?>>Animación</option>
                <option value="Terror" <?php echo $category_filter == 'Terror' ? 'selected' : ''; ?>>Terror</option>
                <option value="Drama" <?php echo $category_filter == 'Drama' ? 'selected' : ''; ?>>Drama</option>
            </select>
        </form>
    </div>

    <div class="row mt-4">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <!-- Tarjeta de película -->
            <div class="movie-card">
                <?php if (!empty($row['imagen'])): ?>
                    <img src="img/<?php echo $row['imagen']; ?>" class="movie-card-img" alt="Imagen de <?php echo htmlspecialchars($row['nombre']); ?>">
                <?php endif; ?>
                <div class="movie-card-title"><?php echo htmlspecialchars($row['nombre']); ?></div>
                <p class="card-text"><strong>Categoría:</strong> <?php echo htmlspecialchars($row['categoria']); ?></p>
                <p class="card-text"><strong>Director:</strong> <?php echo htmlspecialchars($row['director']); ?></p>
                <p class="card-text"><strong>Fecha:</strong> <?php echo htmlspecialchars($row['fecha']); ?></p>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#movieModal<?php echo $row['id']; ?>">Ver Descripción</button>

                <!-- Botón para eliminar -->
                <form method="POST" class="d-inline">
                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta película?')">Eliminar</button>
                </form>
            </div>

            <!-- Modal para la descripción -->
            <div class="modal fade" id="movieModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="movieModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="movieModalLabel<?php echo $row['id']; ?>">Descripción de "<?php echo htmlspecialchars($row['nombre']); ?>"</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</div>
<?php include 'templates/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>