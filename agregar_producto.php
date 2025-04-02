<!-- filepath: c:\xampp\htdocs\cuarto\awos\1_introduccion\techdock\cliente_login\admin\agregar_producto.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/auth.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Agregar Producto</h1>
        <form id="addProductForm" class="border p-4 rounded shadow">
            <div class="mb-3">
                <label for="nom_prod" class="form-label">Nombre del Producto</label>
                <input type="text" id="nom_prod" class="form-control" placeholder="Nombre del producto" required>
            </div>
            <div class="mb-3">
                <label for="descr" class="form-label">Descripción</label>
                <textarea id="descr" class="form-control" placeholder="Descripción"></textarea>
            </div>
            <div class="mb-3">
                <label for="prec" class="form-label">Precio</label>
                <input type="number" id="prec" class="form-control" placeholder="Precio" required>
            </div>
            <div class="mb-3">
                <label for="img" class="form-label">URL de la Imagen</label>
                <input type="text" id="img" class="form-control" placeholder="URL de la imagen">
            </div>
            <div class="mb-3">
                <label for="id_cat" class="form-label">Categoría</label>
                <select id="id_cat" class="form-select" required>
                    <option value="">Seleccione una categoría</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Agregar Producto</button>
            <button type="button" class="btn btn-secondary w-100" onclick="window.location.href='gestionar_producto.php'">Regresar</button>
        </form>
    </div>

    <script>
        // Cargar categorías en el select
        function loadCategories() {
            fetch('http://127.0.0.1:8000/categorias')
                .then(response => response.json())
                .then(data => {
                    const categorySelect = document.getElementById('id_cat');
                    data.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id_cat;
                        option.textContent = category.nom_cat;
                        categorySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error al cargar las categorías:', error));
        }

        // Agregar producto
        document.getElementById('addProductForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const product = {
                nom_prod: document.getElementById('nom_prod').value,
                descr: document.getElementById('descr').value,
                prec: document.getElementById('prec').value,
                img: document.getElementById('img').value,
                id_cat: document.getElementById('id_cat').value,
            };
            fetch('http://127.0.0.1:8000/productos', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(product),
            }).then(() => {
                alert('Producto agregado correctamente');
                window.location.href = 'gestionar_producto.php'; // Redirigir a la vista de gestión
            });
        });

        // Cargar categorías al inicio
        loadCategories();
    </script>
</body>
</html>