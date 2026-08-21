<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Actividades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>

<body class="bg-light">

    <div class="container py-4">
        <h1 class="text-center mb-4">Panel de Actividades</h1>

        <!-- Resumen -->
        <div class="row mb-4 text-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total Actividades</h5>
                        <h2 id="total-actividades">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total Inscripciones</h5>
                        <h2 id="total-inscripciones">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Participación</h5>
                        <h2 id="media-participacion">0%</h2>
                    </div>
                </div>
            </div>
        </div>

       

        
        

        

       <!-- Gráfico -->
        <h3 class="mt-5">Estadísticas</h3>
        <canvas id="grafico-actividades" height="100"></canvas>
    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
        let actividades = JSON.parse(localStorage.getItem("actividades")) || [];
        let inscripciones = JSON.parse(localStorage.getItem("inscripciones")) || [];
        let chart;

        

        // CORREGIDO: NO RENNUMERA LOS IDs
        function corregirIds() {
            inscripciones = inscripciones.map(ins => {
                const act = actividades.find(a => a.nombre === ins.actividad);
                return { ...ins, actividadId: act ? act.id : null };
            });
            guardarStorage();
        }

        function cargarResumen() {
            document.getElementById("total-actividades").textContent = actividades.length;
            document.getElementById("total-inscripciones").textContent = inscripciones.length;
            let media = actividades.length > 0 ? ((inscripciones.length / (actividades.length * 10)) * 100).toFixed(1) : 0;
            document.getElementById("media-participacion").textContent = media + "%";
        }

        function cargarActividades() {
            const tabla = document.getElementById("tabla-actividades");
            tabla.innerHTML = "";
            const select = document.getElementById("actividadInscripcion");
            select.innerHTML = "<option value='' disabled selected>Seleccionar...</option>";

            if (actividades.length === 0) {
                tabla.innerHTML = "<tr><td colspan='7' class='text-center'>No hay actividades registradas</td></tr>";
            } else {
                actividades.forEach(act => {
                    const count = inscripciones.filter(i => i.actividad === act.nombre).length;
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${act.id}</td>
                        <td>${act.nombre}</td>
                        <td>${act.tipo}</td>
                        <td>${act.fecha}</td>
                        <td>${act.lugar}</td>
                        <td>${count} inscritos</td>
                        <td>
                            <button class="btn btn-sm btn-primary me-1" onclick="editarActividad(${act.id})">Editar</button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarActividad(${act.id})">Eliminar</button>
                        </td>`;
                    tabla.appendChild(tr);
                    select.innerHTML += `<option value="${act.id}">${act.nombre}</option>`;
                });
            }

            cargarGraficoInscripciones();
        }

        function cargarInscripciones() {
            const tabla = document.getElementById("tabla-inscripciones");
            tabla.innerHTML = "";
            if (inscripciones.length === 0) {
                tabla.innerHTML = "<tr><td colspan='4' class='text-center'>No hay inscripciones</td></tr>";
            } else {
                inscripciones.forEach((ins, index) => {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${ins.usuario}</td>
                        <td>${ins.actividad}</td>
                        <td><button class="btn btn-sm btn-danger" onclick="eliminarInscripcion(${ins.id})">Eliminar</button></td>`;
                    tabla.appendChild(tr);
                });
            }
        }

        function cargarGraficoInscripciones() {
            const ctx = document.getElementById("grafico-actividades").getContext("2d");
            if (chart) chart.destroy();
            const labels = actividades.map(a => a.nombre);
            const data = actividades.map(a => inscripciones.filter(i => i.actividad === a.nombre).length);
            chart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels,
                    datasets: [{
                        label: "Inscripciones por Actividad",
                        data,
                        backgroundColor: "rgba(54, 162, 235, 0.7)"
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }

        // --- MODALES ---
        function abrirModalActividad() {
            document.getElementById("actividadId").value = "";
            document.getElementById("actividadNombre").value = "";
            document.getElementById("actividadTipo").value = "";
            document.getElementById("actividadFecha").value = "";
            document.getElementById("actividadLugar").value = "";
            modalActividad.show();
        }

        function guardarActividad() {
            const id = document.getElementById("actividadId").value;
            const nombre = document.getElementById("actividadNombre").value;
            const tipo = document.getElementById("actividadTipo").value;
            const fecha = document.getElementById("actividadFecha").value;
            const lugar = document.getElementById("actividadLugar").value;
            if (!nombre || !tipo || !fecha || !lugar) return alert("Completa todos los campos");

            if (id) {
                const act = actividades.find(a => a.id == id);
                act.nombre = nombre;
                act.tipo = tipo;
                act.fecha = fecha;
                act.lugar = lugar;

            } else {
                const nuevoId = actividades.length > 0 ? Math.max(...actividades.map(a => a.id)) + 1 : 1;
                actividades.push({ id: nuevoId, nombre, tipo, fecha, lugar });
            }

            guardarStorage();
            corregirIds();
            cargarActividades();
            cargarResumen();
            modalActividad.hide();
        }

        function editarActividad(id) {
            const act = actividades.find(a => a.id == id);
            document.getElementById("actividadId").value = act.id;
            document.getElementById("actividadNombre").value = act.nombre;
            document.getElementById("actividadTipo").value = act.tipo;
            document.getElementById("actividadFecha").value = act.fecha;
            document.getElementById("actividadLugar").value = act.lugar;
            modalActividad.show();
        }

        function eliminarActividad(id) {
            if (!confirm("¿Eliminar actividad?")) return;
            actividades = actividades.filter(a => a.id != id);
            inscripciones = inscripciones.filter(i => i.actividadId != id);
            guardarStorage();
            corregirIds();
            cargarActividades();
            cargarInscripciones();
            cargarResumen();
        }

        function abrirModalInscripcion() {
            document.getElementById("inscripcionId").value = "";
            document.getElementById("inscripcionUsuario").value = "";
            modalInscripcion.show();
        }

        function guardarInscripcion() {
            const usuario = document.getElementById("inscripcionUsuario").value;
            const actividadId = parseInt(document.getElementById("actividadInscripcion").value);
            const actividad = actividades.find(a => a.id === actividadId);

            if (!usuario || !actividadId) return alert("Completa todos los campos");

            const nuevoId = inscripciones.length > 0 ? Math.max(...inscripciones.map(i => i.id)) + 1 : 1;

            inscripciones.push({ id: nuevoId, usuario, actividad: actividad.nombre, actividadId });

            guardarStorage();
            corregirIds();
            cargarActividades();
            cargarInscripciones();
            cargarResumen();
            modalInscripcion.hide();
        }

        function eliminarInscripcion(id) {
            if (!confirm("¿Eliminar inscripción?")) return;
            inscripciones = inscripciones.filter(i => i.id != id);
            guardarStorage();
            corregirIds();
            cargarActividades();
            cargarInscripciones();
            cargarResumen();
        }

        // Inicializar
        corregirIds();
        cargarActividades();
        cargarInscripciones();
        cargarResumen();
    </script>
</body>

</html>

