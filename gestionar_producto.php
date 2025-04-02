<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
  </style>

</head>
<body>

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

    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestionar Productos</h1>
        <button class="btn btn-primary mb-3" onclick="window.location.href='agregar_producto.php'">Agregar Producto</button>

        <table class="table table-striped" id="productTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        // Obtener productos
        function fetchProducts() {
            fetch('http://127.0.0.1:8000/productos')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#productTable tbody');
                    tableBody.innerHTML = '';
                    data.forEach(product => {
                        tableBody.innerHTML += `
                            <tr>
                                <td>${product.id_prod}</td>
                                <td>${product.nom_prod}</td>
                                <td>${product.descr}</td>
                                <td>$${product.prec}</td>
                                <td>${product.estatus === 1 ? 'Activo' : 'Inactivo'}</td>
                                <td>
                                    <button class="btn ${product.estatus === 1 ? 'btn-danger' : 'btn-success'} btn-sm" 
                                        onclick="toggleProductStatus(${product.id_prod}, ${product.estatus})">
                                        ${product.estatus === 1 ? 'Desactivar' : 'Activar'}
                                    </button>
                                    <button class="btn btn-warning btn-sm" onclick="editProduct(${product.id_prod})">Editar</button>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(error => console.error('Error al cargar los productos:', error));
        }

        // Alternar el estatus del producto
        function toggleProductStatus(id, currentStatus) {
            fetch(`http://127.0.0.1:8000/productos/${id}/toggle-status`, {
                method: 'PATCH',
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensaje);
                fetchProducts(); // Recargar la lista de productos
            })
            .catch(error => console.error('Error:', error));
        }

        // Editar producto (redirigir a una página de edición)
        function editProduct(id) {
            window.location.href = `editar_producto.php?id=${id}`;
        }

        // Cargar productos al inicio
        fetchProducts();
    </script>
</body>
</html>