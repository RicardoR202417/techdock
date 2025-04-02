<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/auth.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Agregar Inventario</h1>
        <form id="addInventoryForm" class="border p-4 rounded shadow">
            <div class="mb-3">
                <label for="id_prod" class="form-label">Producto</label>
                <select id="id_prod" class="form-select" required>
                    <option value="">Seleccione un producto</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="cant_disp" class="form-label">Cantidad Disponible</label>
                <input type="number" id="cant_disp" class="form-control" placeholder="Cantidad disponible" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Agregar Inventario</button>
        </form>

        <!-- Botón de regresar -->
        <div class="text-center mt-3">
            <button class="btn btn-secondary" onclick="window.location.href='gestionar_inventario.php'">Regresar</button>
        </div>
    </div>

    <script>
        // Cargar productos en el select
        function loadProducts() {
            fetch('http://127.0.0.1:8000/productos')
                .then(response => response.json())
                .then(data => {
                    const productSelect = document.getElementById('id_prod');
                    data.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id_prod;
                        option.textContent = product.nom_prod;
                        productSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error al cargar los productos:', error));
        }

        // Agregar inventario
        document.getElementById('addInventoryForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const id_prod = document.getElementById('id_prod').value;
            const cant_disp = document.getElementById('cant_disp').value;

            if (!id_prod || !cant_disp || cant_disp <= 0) {
                alert('Por favor, complete todos los campos correctamente.');
                return;
            }

            fetch('http://127.0.0.1:8000/inventarios', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_prod: parseInt(id_prod), cant_disp: parseInt(cant_disp) }),
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensaje); // Mostrar el mensaje del backend
                window.location.href = 'gestionar_inventario.php'; // Redirigir a la vista de inventarios
            })
            .catch(error => {
                console.error('Error al agregar el inventario:', error);
                alert('Ocurrió un error al agregar el inventario. Por favor, intente nuevamente.');
            });
        });

        // Cargar productos al inicio
        loadProducts();
    </script>
</body>
</html>