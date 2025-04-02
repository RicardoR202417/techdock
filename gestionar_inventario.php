<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
        <h1 class="text-center mb-4">Gestionar Inventario</h1>
        <button class="btn btn-primary mb-3" onclick="window.location.href='agregar_inventario.php'">Agregar Inventario</button>
        <table class="table table-striped" id="inventoryTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Stock Disponible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        // Obtener inventarios
        function fetchInventories() {
            fetch('http://127.0.0.1:8000/inventarios')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error en la solicitud: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const tableBody = document.querySelector('#inventoryTable tbody');
                    tableBody.innerHTML = '';
                    data.forEach(inventory => {
                        tableBody.innerHTML += `
                            <tr>
                                <td>${inventory.id_inv}</td>
                                <td>${inventory.producto.nom_prod}</td>
                                <td>${inventory.cant_disp}</td>
                                <td>
                                    <button class="btn btn-success btn-sm" onclick="updateStock(${inventory.id_inv}, 'increase')">Aumentar</button>
                                    <button class="btn btn-danger btn-sm" onclick="updateStock(${inventory.id_inv}, 'decrease')">Disminuir</button>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(error => {
                    console.error('Error al cargar los inventarios:', error);
                    alert('Ocurrió un error al cargar los inventarios. Por favor, intente nuevamente.');
                });
        }

        // Actualizar stock
        function updateStock(id, action) {
            const cantidad = prompt(`Ingrese la cantidad a ${action === 'increase' ? 'aumentar' : 'disminuir'}:`);
            if (!cantidad || isNaN(cantidad) || cantidad <= 0) {
                alert('Cantidad inválida');
                return;
            }

            fetch(`http://127.0.0.1:8000/inventarios/${id}/${action}`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cantidad: parseInt(cantidad) }),
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensaje);
                fetchInventories(); // Recargar la tabla
            })
            .catch(error => console.error('Error al actualizar el stock:', error));
        }

        // Cargar inventarios al inicio
        fetchInventories();
    </script>
</body>
</html>