document.addEventListener("DOMContentLoaded", () => {
    const datos_usuario = JSON.parse(localStorage.getItem("usuario"));
    const token = localStorage.getItem("token");

    if (!datos_usuario || !token) {
        alert("No estás autenticado. Inicia sesión.");
        window.location.replace("../index.html");
    } else {
        // Verificar el tipo de usuario
        const tipo_usuario = datos_usuario.tipo_usuario;

        // Redirigir según el tipo de usuario
        if (tipo_usuario === 1) {
            // Administrador
            if (!window.location.pathname.includes("/admin/")) {
                alert("Acceso denegado. Redirigiendo al panel de administrador.");
                window.location.replace("../admin/dashboard.php");
            }
        } else if (tipo_usuario === 2) {
            // Cliente
            if (!window.location.pathname.includes("/usuario/")) {
                alert("Acceso denegado. Redirigiendo al catálogo.");
                window.location.replace("../usuario/index.php");
            }
        } else {
            alert("Tipo de usuario no válido. Cerrando sesión.");
            localStorage.clear();
            window.location.replace("../index.html");
        }
    }
});