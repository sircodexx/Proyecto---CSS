<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h1 class="welcome-text">¡Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?>!</h1>
        <div class="button-container">
            <!-- Botón de Registrar Película -->
            <a href="register_movie.php" class="btn btn-green btn-animated">
                <i class="fas fa-film"></i>
                <span>Registrar Película</span>
            </a>
            <!-- Botón de Lista de Películas -->
            <a href="list_movies.php" class="btn btn-blue btn-animated">
                <i class="fas fa-list-alt"></i>
                <span>Lista de Películas</span>
            </a>
            <!-- Botón de Cerrar Sesión -->
            <a href="logout.php" class="btn btn-red btn-animated">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </div>
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 - PelisFlix. Todos los derechos reservados.</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>