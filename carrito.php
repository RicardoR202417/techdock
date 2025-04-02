<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/auth.js"></script>
    <script src="../js/logout.js"></script>

    <style>
    #main-section {
      position: relative;
      background-image: url('../img/banner.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      height: 100;
    }

    .navbar-nav .nav-link {
      color: white;
      font-size: 1.2rem;
      display: flex;
      align-items: center;
    }

    .navbar-nav .nav-link i {
      font-size: 1.5rem;
      margin-right: 8px;
    }

    .navbar-nav {
      justify-content: center;
    }

    .card {
      margin-bottom: 20px;
      height: 100%; /* Asegura que todas las tarjetas ocupen el mismo espacio */
    }

    .card img {
      height: 150px;
      object-fit: cover;
    }

    .card-body {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .card-title {
      font-size: 1.1rem;
      font-weight: bold;
    }

    .card-text {
      font-size: 0.9rem;
    }

    .product-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .btn-group {
      display: flex;
      justify-content: space-between;
      gap: 10px; /* Espaciado entre los botones */
    }

    .btn-group button {
      flex: 1; /* Asegura que los botones tengan el mismo tamaño */
    }
  </style>
    </head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" href="index.php"><i class="fas fa-book"></i></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="carrito.php"><i class="fas fa-shopping-cart"></i></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="logout" href="#"><i class="fas fa-user"></i></a>
        </li>
      </ul>
    </div>
  </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Carrito de Compras</h1>
        <div id="cartItems" class="list-group mb-4"></div>
        <div id="errorContainer" class="alert alert-danger" style="display: none;"></div>
        <div class="text-end">
            <div class="card text-end">
                <div class="card-body">
                    <p class="card-text"><strong>Subtotal:</strong> <span id="subtotal"></span></p>
                    <p class="card-text"><strong>IVA (16%):</strong> <span id="iva"></span></p>
                    <p class="card-text"><strong>Total:</strong> <span id="total"></span></p>
                    <div class="d-flex justify-content-between mt-3">
                        <button class="btn btn-danger" onclick="clearCart()">Vaciar Carrito</button>
                        <button class="btn btn-success" onclick="checkout()">Realizar compra</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Cargar productos del carrito
        function loadCart() {
            fetch('http://127.0.0.1:8000/carrito?id_usuario=1')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error en la solicitud: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const cartItems = document.getElementById('cartItems');
                    cartItems.innerHTML = '';
                    let subtotal = 0;

                    if (data.mensaje) {
                        cartItems.innerHTML = `<p class="text-center">${data.mensaje}</p>`;
                        document.getElementById('subtotal').textContent = '';
                        document.getElementById('iva').textContent = '';
                        document.getElementById('total').textContent = '';
                        return;
                    }

                    data.productos.forEach(item => {
                        const itemSubtotal = item.producto.prec * item.cantidad;
                        subtotal += itemSubtotal;

                        cartItems.innerHTML += `
                            <div class="card mb-3">
                                <div class="row g-0 align-items-center">
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <h5 class="card-title">${item.producto.nom_prod}</h5>
                                            <p class="card-text mb-1">Precio: $${item.producto.prec}</p>
                                            <p class="card-text mb-1">Cantidad: ${item.cantidad}</p>
                                            <p class="card-text mb-1">Subtotal: $${itemSubtotal.toFixed(2)}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <button class="btn btn-sm btn-secondary" onclick="updateQuantity(${item.id}, ${item.cantidad - 1})">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span class="badge bg-primary text-white">${item.cantidad}</span>
                                            <button class="btn btn-sm btn-primary" onclick="updateQuantity(${item.id}, ${item.cantidad + 1})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger ms-2" onclick="removeFromCart(${item.id})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    const iva = subtotal * 0.16;
                    const total = subtotal + iva;

                    document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
                    document.getElementById('iva').textContent = `$${iva.toFixed(2)}`;
                    document.getElementById('total').textContent = `$${total.toFixed(2)}`;
                })
                .catch(error => console.error('Error al cargar el carrito:', error));
        }

        // Actualizar cantidad de un producto
        function updateQuantity(productId, newQuantity) {
            if (newQuantity < 1) {
                alert('La cantidad no puede ser menor a 1.');
                return;
            }

            fetch(`http://127.0.0.1:8000/carrito/actualizar/${productId}`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cantidad: newQuantity }),
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(error => {
                        throw new Error(error.mensaje || `Error en la solicitud: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                alert(data.mensaje); // Mostrar mensaje de confirmación
                loadCart(); // Recargar el carrito después de actualizar
            })
            .catch(error => {
                alert(error.message); // Mostrar el mensaje de error como una alerta
                console.error('Error al actualizar la cantidad:', error);
            });
        }

        // Eliminar un producto del carrito
        function removeFromCart(productId) {
            fetch(`http://127.0.0.1:8000/carrito/eliminar/${productId}`, {
                method: 'DELETE',
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en la solicitud: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                alert(data.mensaje);
                loadCart(); // Recargar el carrito después de eliminar
            })
            .catch(error => console.error('Error al eliminar el producto:', error));
        }

        // Vaciar el carrito
        function clearCart() {
            fetch('http://127.0.0.1:8000/carrito/vaciar?id_usuario=1', {
                method: 'DELETE',
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en la solicitud: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                alert(data.mensaje);
                loadCart(); // Recargar el carrito después de vaciarlo
            })
            .catch(error => console.error('Error al vaciar el carrito:', error));
        }

        // Realizar compra
        function checkout() {
            fetch('http://127.0.0.1:8000/carrito/comprar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_usuario: 1 }),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en la solicitud: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                alert(data.mensaje);
                loadCart(); // Recargar el carrito después de realizar la compra
            })
            .catch(error => console.error('Error al realizar la compra:', error));
        }

        // Actualizar un producto del carrito
        function updateCartItem(cartItemId, newQuantity) {
            fetch(`http://127.0.0.1:8000/carrito/update/${cartItemId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cantidad: newQuantity }),
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(error => {
                        throw new Error(error.mensaje || `Error en la solicitud: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                alert(data.mensaje); // Mostrar mensaje de confirmación
                // Opcional: Recargar el carrito para reflejar los cambios
                loadCart();
            })
            .catch(error => alert(error.message)); // Mostrar mensaje de error
        }

        // Cargar el carrito al inicio
        loadCart();
    </script>
</body>
</html>