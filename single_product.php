<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalles del Producto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="../js/auth.js"></script>
</head>

<body>
  <div class="container mt-5">
    <div id="product-details" class="text-center">
      <img id="productImage" class="img-fluid mb-3" alt="Imagen del producto" style="max-height: 300px;">
      <h1 id="productName" class="mb-3"></h1>
      <h3 id="productPrice" class="mb-3 text-success"></h3>
      <p id="productDescription" class="mb-3"></p>
    </div>

    <div class="text-center mt-3">
      <button class="btn btn-secondary" onclick="window.history.back()">Regresar</button>
    </div>
  </div>

  <script>
    function loadProductDetails(productId) {
        fetch(`http://127.0.0.1:8000/producto/${productId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en la solicitud: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('productImage').src = data.imagen || '../img/placeholder.png';
                document.getElementById('productName').textContent = data.nom_prod;
                document.getElementById('productPrice').textContent = `$${data.prec}`;
                document.getElementById('productDescription').textContent = data.desc;
            })
            .catch(error => console.error('Error al cargar los detalles del producto:', error));
    }

    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    loadProductDetails(productId);
  </script>
</body>

</html>