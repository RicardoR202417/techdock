$(document).ready(function () {

    //Vamos a validar si un usuario ya está logueado
    if(localStorage.getItem("usuario")){
        redirigirUsuario();
    }

    document.getElementById("loginForm").addEventListener("submit", async (e) => {
        e.preventDefault();
    
        const usuario = document.getElementById("usuario").value;
        const clave = document.getElementById("clave").value;
    
        const response = await fetch("http://127.0.0.1:8000/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ usuario, clave }),
        });
    
        const data = await response.json();
        if (data.estatus === "exitoso") {
            localStorage.setItem("usuario", JSON.stringify(data.usuario));
            localStorage.setItem("token", data.token);
            window.location.href = data.usuario.tipo_usuario === 1 ? "admin/dashboard.php" : "usuario/index.php";
        } else {
            mostrarError(data.mensaje);
        }
    });

    function redirigirUsuario() {
        let usuario = JSON.parse(localStorage.getItem("usuario"));
        if (usuario.tipo_usuario == 1) {
          window.location.replace("admin/dashboard.php");
        } else {
          window.location.replace("usuario/index.php");
        }
      }
      function mostrarError(mensaje) {
        const errorMsg = document.getElementById("error-msg");
        errorMsg.textContent = mensaje;
        errorMsg.classList.remove("d-none");
      }
});