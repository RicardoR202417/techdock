function addToCart(productId, quantity = 1) {
    fetch('http://127.0.0.1:8000/carrito/agregar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id_usuario: 1, // Cambia esto si el ID del usuario es dinámico
            id_prod: productId,
            cantidad: quantity,
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
    .catch(error => alert(error.message)); // Mostrar mensaje de error
}

function checkout() {
    const metodoPago = prompt("Ingrese el método de pago (e.g., Tarjeta, PayPal):");
    if (!metodoPago) {
        alert("Debe ingresar un método de pago.");
        return;
    }

    fetch('http://127.0.0.1:8000/api/checkout', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id_usuario: 1, // Cambiar por el ID del usuario autenticado
            productos: cartItems, // cartItems debe contener los productos del carrito
            metodo_pago: metodoPago,
        }),
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(error => { throw new Error(error.error); });
        }
        return response.json();
    })
    .then(data => {
        alert(data.mensaje);
        clearCart(); // Vaciar el carrito después del checkout
    })
    .catch(error => {
        alert(`Error al realizar el checkout: ${error.message}`);
    });
}