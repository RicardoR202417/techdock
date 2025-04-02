<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ADMINISTRADOR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="../js/auth.js"></script>
  <style>
    .navbar-nav .nav-link {
      color: white;
      font-size: 1.2rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .navbar-nav .nav-link i {
      font-size: 1.5rem;
      margin-bottom: 4px;
    }
    .navbar-nav {
      justify-content: center;
    }
    .logo img {
      width: 200px;
      height: auto;
    }
  </style>
</head>

<body>
  <script>
    let datos_usuario = JSON.parse(localStorage.getItem("usuario"));
    let token = localStorage.getItem("token");

    if (!datos_usuario || !token) {
        alert("No estás autenticado. Inicia sesión.");
        window.location.replace("../index.html");
    } else if (datos_usuario.tipo_usuario != 1) {
        alert("Acceso denegado");
        window.location.replace("../index.html");
    } else {
        document.getElementById("nombre").textContent = datos_usuario.nombre;
    }
  </script>

  <!-- Barra de navegación -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link active" href="dashboard.php"><i class="fas fa-home"></i></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="usuarios.php"><i class="fas fa-users"></i></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="gestionar_producto.php"><i class="fas fa-box"></i></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="gestionar_inventario.php"><i class="fas fa-warehouse"></i></a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container text-center">
    <div class="logo">
        <img src="../img/logotipo.png" alt="logotipo">
    </div>
    <h1 class="text-center">¡BIENVENIDO! <span id="nombre"></span></h1>
    <h2 class="text-center">Panel de Administrador</h2>
    <div class="text-center mt-4">
      <button id="logout" class="btn btn-danger">Cerrar Sesión</button>
    </div>
  </div>

  <footer class="text-center mt-4">
    <p>&copy; 2025 TechDock Widgets</p>
  </footer>

  <script src="../js/logout.js"></script>
</body>

</html>