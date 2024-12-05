// Funciones para cambiar la pagina
function irAInicio(){
    window.location = "main.html"
}

function irAMiCuenta(){
    window.location = "micuenta.php"
}

function irAContacto(){
    window.location = "contacto.html"
}

function irAFacturacion(){
    window.location = "facturacion.html"
}

function irATeorico(){
    window.location = "teorico.html"
}

// Lista para almacenar las tarjetas
let tarjetas = [];

// Función para agregar un método de pago
function agregarMetodo(event) {
    event.preventDefault();

    const numeroTarjeta = document.getElementById("numero_tarjeta").value;
    const fechaVencimiento = document.getElementById("fecha_vencimiento_tarjeta").value;
    const nombreTarjeta = document.getElementById("nombre_tarjeta").value;

    console.log("Fecha de vencimiento enviada:", fechaVencimiento); // Verifica qué valor se está enviando

    fetch("guardarTarjeta.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `numero=${numeroTarjeta}&vencimiento=${fechaVencimiento}&nombre=${nombreTarjeta}`,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Respuesta del servidor:", data);
            if (data.status === "success") {
                alert(data.message);
                cargarTarjetas(); // Actualiza las tarjetas después de agregar
                document.getElementById("agregarForm").reset();
            } else {
                alert(`Error: ${data.message}`);
            }
        })
        .catch(error => {
            console.error("Error al guardar tarjeta:", error);
            alert("Hubo un problema al guardar la tarjeta. Revisa la consola.");
        });
}




// Función para mostrar las tarjetas
function cargarTarjetas() {
    fetch("cargarTarjeta.php")
        .then(response => {
            console.log("Estado HTTP:", response.status);
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Datos cargados:", data); // Verifica el contenido aquí
            const contenedor = document.getElementById("metodosPago");
            contenedor.innerHTML = "";

            if (data.length === 0) {
                contenedor.innerHTML = "<p>No hay métodos de pago registrados.</p>";
                return;
            }

            data.forEach((tarjeta) => {
                const tarjetaDiv = document.createElement("div");
                tarjetaDiv.style =
                    "margin-bottom: 15px; background: #fff59d; padding: 10px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);";

                tarjetaDiv.innerHTML = `
                    <p><strong>Tarjeta:</strong> ${tarjeta.numero}</p>
                    <p><strong>Vencimiento:</strong> ${tarjeta.vencimiento}</p>
                    <p><strong>Nombre:</strong> ${tarjeta.nombre}</p>
                    <button style="background: red; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;" onclick="eliminarTarjeta('${tarjeta.numero}')">Eliminar</button>
                `;

                contenedor.appendChild(tarjetaDiv);
            });
        })
        .catch((error) => {
            console.error("Error al cargar tarjetas:", error);
            
        });
}


function eliminarTarjeta(numero) {
    if (!confirm("¿Estás seguro de que quieres eliminar esta tarjeta?")) {
        return;
    }

    fetch("eliminarTarjetas.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `numero=${numero}`,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "success") {
                alert(data.message);
                cargarTarjetas(); // Actualiza la lista después de eliminar
            } else {
                alert(`Error: ${data.message}`);
            }
        })
        .catch((error) => {
            console.error("Error al eliminar tarjeta:", error);
            alert("Hubo un problema al eliminar la tarjeta. Revisa la consola.");
        });
}




// Llama a cargarTarjetas al cargar la página
document.addEventListener("DOMContentLoaded", cargarTarjetas);



// Función para ocultar el formulario
function ocultarFormularioAgregar() {
    document.getElementById("agregarForm").style.display = "none";
}
