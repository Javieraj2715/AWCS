// Funciones para cambiar la página
function irAInicio() {
    window.location = "main.html";
}

function irAMiCuenta() {
    window.location = "micuenta.php";
}

function irAContacto() {
    window.location = "contacto.html";
}

function irAFacturacion() {
    window.location = "facturacion.html";
}

function irATeorico() {
    window.location = "teorico.html";
}

// Lista para almacenar las tarjetas
let tarjetas = [];

// Función para agregar un método de pago
function agregarMetodo(event) {
    event.preventDefault();

    const numeroTarjeta = document.getElementById("numero_tarjeta").value;
    const fechaVencimiento = document.getElementById("fecha_vencimiento_tarjeta").value;
    const nombreTarjeta = document.getElementById("nombre_tarjeta").value;

    console.log("Fecha de vencimiento enviada:", fechaVencimiento); // Depuración

    fetch("guardarTarjeta.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `numero=${numeroTarjeta}&vencimiento=${fechaVencimiento}&nombre=${nombreTarjeta}`,
    })
        .then(response => {
            if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
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
            alert("Hubo un problema al guardar la tarjeta.");
        });
}

// Funcion para cargarTarjetas

function cargarTarjetas(context = "metodosPago") {
    fetch("cargarTarjeta.php")
        .then(response => {
            console.log("Estado HTTP:", response.status);
            if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
            return response.json();
        })
        .then(data => {
            console.log("Tarjetas cargadas:", data);

            if (context === "metodosPago") {
                const contenedor = document.getElementById("metodosPago");

                if (!contenedor) {
                    console.warn("Elemento 'metodosPago' no encontrado. No se ejecuta cargarTarjetas() en este contexto.");
                    return;
                }

                contenedor.innerHTML = ""; // Limpia el contenedor

                if (data.length === 0) {
                    contenedor.innerHTML = "<p>No hay métodos de pago registrados.</p>";
                    return;
                }

                // Mostrar las tarjetas en miCuenta.php
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
            } else if (context === "tarjetaDropdown") {
                const selectTarjeta = document.getElementById("tarjeta");

                if (!selectTarjeta) {
                    console.warn("Elemento 'tarjeta' no encontrado. No se ejecuta cargarTarjetas() en este contexto.");
                    return;
                }

                selectTarjeta.innerHTML = ""; // Limpia el dropdown

                if (data.length === 0) {
                    selectTarjeta.innerHTML = '<option value="">No hay tarjetas registradas</option>';
                    return;
                }

                // Cargar las tarjetas en el dropdown
                data.forEach((tarjeta) => {
                    const option = document.createElement("option");
                    option.value = tarjeta.numero; // Usamos el número de tarjeta como valor
                    option.textContent = `Tarjeta ${tarjeta.numero} - ${tarjeta.nombre}`;
                    selectTarjeta.appendChild(option);
                });
            }
        })
        .catch(error => console.error("Error al cargar tarjetas:", error));
}




// Función para eliminar una tarjeta
function eliminarTarjeta(numero) {
    if (!confirm("¿Estás seguro de que quieres eliminar esta tarjeta?")) return;

    fetch("eliminarTarjetas.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `numero=${numero}`,
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert(data.message);
                cargarTarjetas();
            } else {
                alert(`Error: ${data.message}`);
            }
        })
        .catch(error => console.error("Error al eliminar tarjeta:", error));
}

// Función para cargar las facturas
function cargarFacturas() {
    fetch("obtenerFacturas.php")
        .then(response => response.json())
        .then(data => {
            console.log("Facturas cargadas:", data);
            const listaPendientes = document.getElementById("listaPendientes");
            const selectFactura = document.getElementById("factura");
            const estadoCuenta = document.getElementById("estadoCuenta");

            // Validación para evitar errores si los elementos no existen
            if (!listaPendientes || !selectFactura || !estadoCuenta) {
                console.warn("Elementos de facturación no encontrados. No se ejecuta cargarFacturas().");
                return;
            }

            listaPendientes.innerHTML = "";
            selectFactura.innerHTML = "";
            estadoCuenta.innerHTML = "";

            let totalPendiente = 0;
            let totalPagado = 0;
            let ultimoPago = "N/A";

            data.forEach(factura => {
                const estadoFactura = factura.estado || "Pendiente"; // Valor predeterminado
                const li = document.createElement("li");
                li.textContent = `Factura #${factura.factura_id} - ₡${factura.monto} - ${factura.tipo_examen} - ${factura.fecha_examen} - ${factura.provincia} - Estado: ${estadoFactura}`;
                listaPendientes.appendChild(li);

                if (estadoFactura === "Pendiente") {
                    totalPendiente += parseFloat(factura.monto);
                    const option = document.createElement("option");
                    option.value = factura.factura_id;
                    option.textContent = `Factura #${factura.factura_id} - ₡${factura.monto}`;
                    selectFactura.appendChild(option);
                } else if (estadoFactura === "Pagada") {
                    totalPagado += parseFloat(factura.monto);
                    ultimoPago = factura.fecha_pago || "N/A";
                }
            });

            estadoCuenta.innerHTML = `
                <li>Total Pendiente: ₡${totalPendiente}</li>
                <li>Total Pagado: ₡${totalPagado}</li>
                <li>Último Pago: ${ultimoPago}</li>
            `;

            if (selectFactura.options.length === 0) {
                selectFactura.innerHTML = '<option value="">No hay facturas pendientes</option>';
                document.getElementById("formPagar").querySelector('button[type="submit"]').disabled = true;
            } else {
                document.getElementById("formPagar").querySelector('button[type="submit"]').disabled = false;
            }
        })
        .catch(error => console.error("Error al cargar facturas:", error));
}

// Función para procesar el pago de una factura
function procesarPago(event) {
    event.preventDefault();

    const facturaId = document.getElementById("factura").value;

    if (!facturaId) {
        alert("No hay facturas pendientes para pagar.");
        return;
    }

    fetch("pagarFactura.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `factura_id=${facturaId}`,
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert(data.message);
                cargarFacturas();
            } else {
                alert(`Error: ${data.message}`);
            }
        })
        .catch(error => console.error("Error al procesar el pago:", error));
}

// Ejecuta solo si los elementos existen
document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("listaPendientes")) {
        cargarFacturas();
    }
    if (document.getElementById("metodosPago")) {
        cargarTarjetas();
    }
});
