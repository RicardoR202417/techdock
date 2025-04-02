<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuario index</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="../js/auth.js"></script>
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
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      let datos_usuario = JSON.parse(localStorage.getItem("usuario"));
      let token = localStorage.getItem("token");

      if (!datos_usuario || !token) {
          alert("No estás autenticado. Inicia sesión.");
          window.location.replace("../index.html");
      } else if (datos_usuario.tipo_usuario != 2) {
          alert("Acceso denegado");
          window.location.replace("../index.html");
      }
    });
  </script>

  <!-- Barra de navegación -->
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

  <div class="container text-center">
    <section id="main-section" class="py-5 text-white">
      <div class="shadow container text-center">
        <h1 class="fw-bold text-outline">TECHDOCK</h1>
        <p class="fs-4 fw-bold text-outline">Encuentra los mejores widgets del mercado</p>
      </div>
    </section>

    <h2 class="text-center my-4">CATÁLOGO</h2>

    <!-- Filtro por categoría -->
    <div class="mb-4">
      <select id="categoryFilter" class="form-select w-50 mx-auto">
        <option value="">Todas las categorías</option>
      </select>
    </div>

    <div class="row mt-4" id="catalog">
      <!-- Los productos se cargarán dinámicamente aquí -->
    </div>
  </div>

  <footer class="text-center mt-4">
    <p>&copy; 2025 TechDock Widgets</p>
  </footer>

  <script src="../js/logout.js"></script>
  <script src="../js/cart.js"></script>
  <script>
    // Cargar categorías en el filtro
    fetch('http://127.0.0.1:8000/categorias')
      .then(response => response.json())
      .then(data => {
        const categoryFilter = document.getElementById('categoryFilter');
        data.forEach(category => {
          const option = document.createElement('option');
          option.value = category.id_cat;
          option.textContent = category.nom_cat;
          categoryFilter.appendChild(option);
        });
      });

    // Función para cargar productos
    function loadProducts(categoryId = '') {
      let url = 'http://127.0.0.1:8000/productos?only_active=true'; // Solicitar solo productos activos
      if (categoryId) {
        url += `&id_cat=${categoryId}`; // Filtrar por categoría si se selecciona
      }

      fetch(url)
        .then(response => {
          if (!response.ok) {
            throw new Error(`Error en la solicitud: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          const catalog = document.getElementById('catalog');
          catalog.innerHTML = ''; // Limpiar el contenido previo
          if (data.length === 0) {
            catalog.innerHTML = '<p class="text-center">No hay productos disponibles en esta categoría.</p>';
            return;
          }
          data.forEach(product => {
            catalog.innerHTML += `
              <div class="col-md-3 d-flex">
                <div class="card w-100">
                  <img src="${product.img}" class="card-img-top" alt="${product.nom_prod}">
                  <div class="card-body">
                    <h5 class="card-title">${product.nom_prod}</h5>
                    <p class="card-text">${product.descr}</p>
                    <p class="card-text fw-bold">Precio: $${product.prec}</p>
                    <div class="btn-group">
                      <button class="btn btn-primary btn-sm" onclick="addToCart(${product.id_prod})">Agregar al carrito</button>
                      <button class="btn btn-secondary btn-sm" onclick="viewDetails(${product.id_prod})">Ver detalles</button>
                    </div>
                  </div>
                </div>
              </div>
            `;
          });
        })
        .catch(error => console.error('Error al cargar los productos:', error));
    }

    // Cargar todos los productos al inicio
    loadProducts();

    // Filtrar productos por categoría
    document.getElementById('categoryFilter').addEventListener('change', function () {
      const categoryId = this.value;
      loadProducts(categoryId);
    });

    // Función para ver detalles del producto
    function viewDetails(productId) {
      window.location.href = `single_product.php?id=${productId}`;
    }

    // Función para agregar al carrito
    function addToCart(productId) {
      const cantidad = 1; // Cantidad predeterminada

      fetch('http://127.0.0.1:8000/carrito/agregar', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
              id_usuario: 1, // Cambia esto si el ID del usuario es dinámico
              id_prod: productId,
              cantidad: cantidad,
          }),
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
      })
      .catch(error => {
          alert(error.message); // Mostrar el mensaje de error como una alerta
          console.error('Error al agregar al carrito:', error);
      });
    }
  </script>
</body>

</html>