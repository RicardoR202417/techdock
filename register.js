document.getElementById("registerForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const nombre = document.getElementById("nombre").value;
    const correo = document.getElementById("correo").value;
    const usuario = document.getElementById("usuario").value;
    const clave = document.getElementById("clave").value;

    const response = await fetch("http://127.0.0.1:8000/register", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ nombre, correo, usuario, clave }),
    });

    const data = await response.json();
    if (data.estatus === "exitoso") {
        document.getElementById("success-msg").textContent = "Registro exitoso. Ahora puedes iniciar sesión.";
        document.getElementById("success-msg").classList.remove("d-none");
        document.getElementById("error-msg").classList.add("d-none");
    } else {
        document.getElementById("error-msg").textContent = data.mensaje;
        document.getElementById("error-msg").classList.remove("d-none");
        document.getElementById("success-msg").classList.add("d-none");
    }
});

function mostrarError(mensaje) {
    document.getElementById("error-msg").textContent = mensaje;
    document.getElementById("error-msg").classList.remove("d-none");
    document.getElementById("success-msg").classList.add("d-none");
}