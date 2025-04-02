$(document).ready(function(){
    $("#logout").click(function () {
      let token = localStorage.getItem("token");
  
      fetch("http://localhost:8000/logout", {
        method: "POST",
        headers: {
          "Authorization": `Bearer ${token}`,
          "Content-Type": "application/json"
        }
      })
  
        .then(response => response.json())
        .then(data => {
          if (data.estatus == "exitoso") {
            localStorage.clear();
            alert(data.mensaje);
            window.location.replace("../index.html");
          } else {
            alert(data.mensaje);
          }
        })
        .catch(error => alert("Error en el servidor"));
    })
  })