<?php

/*
|--------------------------------------------------------------------------
| CONEXIÓN A LA BASE DE DATOS
|--------------------------------------------------------------------------
*/

require_once '../servidor/conexionBD.php';


/*
|--------------------------------------------------------------------------
| RESPUESTAS AJAX
|--------------------------------------------------------------------------
*/

if (isset($_GET['accion'])) {

    header('Content-Type: application/json; charset=utf-8');


    /*
    |--------------------------------------------------------------------------
    | LISTAR ACTIVIDADES
    |--------------------------------------------------------------------------
    */

    if ($_GET['accion'] === 'listar') {

        $sql = "
            SELECT
                a.id_actividad,
                a.nombre_actividad,
                a.tipo_actividad,
                a.fecha_inicio,
                a.fecha_fin,
                a.hora_inicio,
                a.hora_fin,
                a.dias_semana,
                a.duracion,
                a.requisitos,
                a.costo,
                a.cupo_maximo,
                a.cupo_disponible,
                a.descripcion,
                a.lugar,
                a.id_evento,
                a.estado,
                a.fecha_creacion
            FROM actividades a
            ORDER BY a.id_actividad DESC
        ";


        $resultado = mysqli_query($conexion, $sql);


        if (!$resultado) {

            echo json_encode([
                'success' => false,
                'mensaje' => 'Error al consultar actividades: ' . mysqli_error($conexion)
            ]);

            exit;
        }


        $actividadesBD = [];


        while ($fila = mysqli_fetch_assoc($resultado)) {

            $actividadesBD[] = [

                'id' => (int)$fila['id_actividad'],

                'nombre' => $fila['nombre_actividad'],

                'tipo' => $fila['tipo_actividad'],

                'fecha' => $fila['fecha_inicio'],

                'fecha_fin' => $fila['fecha_fin'],

                'hora_inicio' => $fila['hora_inicio'],

                'hora_fin' => $fila['hora_fin'],

                'dias_semana' => $fila['dias_semana'],

                'duracion' => $fila['duracion'],

                'requisitos' => $fila['requisitos'],

                'costo' => $fila['costo'],

                'cupo_maximo' => $fila['cupo_maximo'],

                'cupo_disponible' => $fila['cupo_disponible'],

                'descripcion' => $fila['descripcion'],

                'lugar' => $fila['lugar'],

                'id_evento' => $fila['id_evento'],

                'estado' => $fila['estado']

            ];
        }


        echo json_encode([

            'success' => true,

            'actividades' => $actividadesBD

        ]);

        exit;
    }



    /*
    |--------------------------------------------------------------------------
    | GUARDAR ACTIVIDAD
    |--------------------------------------------------------------------------
    */

    if ($_GET['accion'] === 'guardar') {

        $datos = json_decode(
            file_get_contents("php://input"),
            true
        );


        $nombre = trim(
            $datos['nombre'] ?? ''
        );


        $tipo = trim(
            $datos['tipo'] ?? ''
        );


        $fecha = trim(
            $datos['fecha'] ?? ''
        );


        $lugar = trim(
            $datos['lugar'] ?? ''
        );


        /*
        |--------------------------------------------------------------------------
        | DÍAS DE LA SEMANA
        |--------------------------------------------------------------------------
        */

        $diasSemana = $datos['dias_semana'] ?? [];


        if (!is_array($diasSemana)) {

            $diasSemana = [];

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN
        |--------------------------------------------------------------------------
        */

        if (
            $nombre === '' ||
            $tipo === '' ||
            $fecha === '' ||
            $lugar === '' ||
            count($diasSemana) === 0
        ) {

            echo json_encode([

                'success' => false,

                'mensaje' => 'Completa todos los campos y selecciona al menos un día.'

            ]);

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | DÍAS PERMITIDOS
        |--------------------------------------------------------------------------
        */

        $diasPermitidos = [

            'Lunes',
            'Martes',
            'Miércoles',
            'Jueves',
            'Viernes',
            'Sábado',
            'Domingo'

        ];


        /*
        |--------------------------------------------------------------------------
        | FILTRAR DÍAS
        |--------------------------------------------------------------------------
        */

        $diasValidos = [];


        foreach ($diasSemana as $dia) {

            if (in_array($dia, $diasPermitidos, true)) {

                $diasValidos[] = $dia;

            }
        }


        if (count($diasValidos) === 0) {

            echo json_encode([

                'success' => false,

                'mensaje' => 'Los días seleccionados no son válidos.'

            ]);

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | FORMATO PARA MYSQL SET
        |--------------------------------------------------------------------------
        */

        $diasSemanaBD = implode(
            ',',
            $diasValidos
        );


        /*
        |--------------------------------------------------------------------------
        | VALORES POR DEFECTO
        |--------------------------------------------------------------------------
        */

        $fechaInicio = $fecha;

        $fechaFin = $fecha;

        $horaInicio = '08:00:00';

        $horaFin = '10:00:00';

        $duracion = '1 día';

        $requisitos = null;

        $costo = 0.00;

        $cupoMaximo = 30;

        $cupoDisponible = 30;

        $descripcion =
            'Actividad registrada desde el Panel de Actividades.';

        $estado = 'Activo';


        /*
        |--------------------------------------------------------------------------
        | BUSCAR EVENTO ACTIVO
        |--------------------------------------------------------------------------
        */

        $sqlEvento = "
            SELECT id_evento
            FROM eventos
            WHERE estado = 'Activo'
            ORDER BY id_evento ASC
            LIMIT 1
        ";


        $resultadoEvento =
            mysqli_query(
                $conexion,
                $sqlEvento
            );


        if (
            !$resultadoEvento ||
            mysqli_num_rows($resultadoEvento) === 0
        ) {

            echo json_encode([

                'success' => false,

                'mensaje' =>
                    'No existe un evento activo para asociar la actividad.'

            ]);

            exit;
        }


        $evento =
            mysqli_fetch_assoc(
                $resultadoEvento
            );


        $idEvento =
            (int)$evento['id_evento'];


        /*
        |--------------------------------------------------------------------------
        | INSERTAR ACTIVIDAD
        |--------------------------------------------------------------------------
        */

        $sql = "
            INSERT INTO actividades
            (
                nombre_actividad,
                tipo_actividad,
                fecha_inicio,
                fecha_fin,
                dias_semana,
                hora_inicio,
                hora_fin,
                duracion,
                requisitos,
                costo,
                cupo_maximo,
                cupo_disponible,
                descripcion,
                lugar,
                id_evento,
                estado
            )
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";


        $stmt =
            mysqli_prepare(
                $conexion,
                $sql
            );


        if (!$stmt) {

            echo json_encode([

                'success' => false,

                'mensaje' =>
                    'Error al preparar el registro: ' .
                    mysqli_error($conexion)

            ]);

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | PARÁMETROS
        |--------------------------------------------------------------------------
        */

        mysqli_stmt_bind_param(

            $stmt,

            "ssssssssdiiissis",

            $nombre,
            $tipo,
            $fechaInicio,
            $fechaFin,
            $diasSemanaBD,
            $horaInicio,
            $horaFin,
            $duracion,
            $requisitos,
            $costo,
            $cupoMaximo,
            $cupoDisponible,
            $descripcion,
            $lugar,
            $idEvento,
            $estado
        );


        /*
        |--------------------------------------------------------------------------
        | EJECUTAR
        |--------------------------------------------------------------------------
        */

        if (!mysqli_stmt_execute($stmt)) {

            echo json_encode([

                'success' => false,

                'mensaje' =>
                    'No se pudo registrar la actividad: ' .
                    mysqli_stmt_error($stmt)

            ]);

            exit;
        }


        $nuevoId =
            mysqli_insert_id(
                $conexion
            );


        mysqli_stmt_close($stmt);


        echo json_encode([

            'success' => true,

            'mensaje' =>
                'Actividad registrada correctamente.',

            'id' => $nuevoId

        ]);

        exit;
    }



    /*
    |--------------------------------------------------------------------------
    | EDITAR ACTIVIDAD
    |--------------------------------------------------------------------------
    */

    if ($_GET['accion'] === 'editar') {

        $datos =
            json_decode(
                file_get_contents("php://input"),
                true
            );


        $id =
            (int)($datos['id'] ?? 0);


        $nombre =
            trim(
                $datos['nombre'] ?? ''
            );


        $tipo =
            trim(
                $datos['tipo'] ?? ''
            );


        $fecha =
            trim(
                $datos['fecha'] ?? ''
            );


        $lugar =
            trim(
                $datos['lugar'] ?? ''
            );


        $diasSemana =
            $datos['dias_semana'] ?? [];


        if (!is_array($diasSemana)) {

            $diasSemana = [];

        }


        if (
            $id <= 0 ||
            $nombre === '' ||
            $tipo === '' ||
            $fecha === '' ||
            $lugar === '' ||
            count($diasSemana) === 0
        ) {

            echo json_encode([

                'success' => false,

                'mensaje' =>
                    'Completa todos los campos.'

            ]);

            exit;
        }


        $diasPermitidos = [

            'Lunes',
            'Martes',
            'Miércoles',
            'Jueves',
            'Viernes',
            'Sábado',
            'Domingo'

        ];


        $diasValidos = [];


        foreach ($diasSemana as $dia) {

            if (
                in_array(
                    $dia,
                    $diasPermitidos,
                    true
                )
            ) {

                $diasValidos[] = $dia;

            }
        }


        $diasSemanaBD =
            implode(
                ',',
                $diasValidos
            );


        $sql = "
            UPDATE actividades
            SET
                nombre_actividad = ?,
                tipo_actividad = ?,
                fecha_inicio = ?,
                fecha_fin = ?,
                dias_semana = ?,
                lugar = ?
            WHERE id_actividad = ?
        ";


        $stmt =
            mysqli_prepare(
                $conexion,
                $sql
            );


        if (!$stmt) {

            echo json_encode([

                'success' => false,

                'mensaje' =>
                    mysqli_error($conexion)

            ]);

            exit;
        }


        mysqli_stmt_bind_param(

            $stmt,

            "ssssssi",

            $nombre,

            $tipo,

            $fecha,

            $fecha,

            $diasSemanaBD,

            $lugar,

            $id

        );


        if (!mysqli_stmt_execute($stmt)) {

            echo json_encode([

                'success' => false,

                'mensaje' =>
                    mysqli_stmt_error($stmt)

            ]);

            exit;
        }


        mysqli_stmt_close($stmt);


        echo json_encode([

            'success' => true,

            'mensaje' =>
                'Actividad actualizada correctamente.'

        ]);

        exit;
    }



    /*
    |--------------------------------------------------------------------------
    | ELIMINAR ACTIVIDAD
    |--------------------------------------------------------------------------
    */

    if ($_GET['accion'] === 'eliminar') {

        $id =
            (int)($_GET['id'] ?? 0);


        if ($id <= 0) {

            echo json_encode([

                'success' => false,

                'mensaje' =>
                    'ID de actividad inválido.'

            ]);

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | ELIMINAR INSCRIPCIONES RELACIONADAS
        |--------------------------------------------------------------------------
        */

        $sqlInscripciones = "
            DELETE FROM inscripcion
            WHERE id_actividad = ?
        ";


        $stmtInscripcion =
            mysqli_prepare(
                $conexion,
                $sqlInscripciones
            );


        if ($stmtInscripcion) {

            mysqli_stmt_bind_param(

                $stmtInscripcion,

                "i",

                $id

            );


            mysqli_stmt_execute(
                $stmtInscripcion
            );


            mysqli_stmt_close(
                $stmtInscripcion
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ELIMINAR ACTIVIDAD
        |--------------------------------------------------------------------------
        */

        $sql = "
            DELETE FROM actividades
            WHERE id_actividad = ?
        ";


        $stmt =
            mysqli_prepare(
                $conexion,
                $sql
            );


        if (!$stmt) {

            echo json_encode([

                'success' => false,

                'mensaje' =>
                    mysqli_error($conexion)

            ]);

            exit;
        }


        mysqli_stmt_bind_param(

            $stmt,

            "i",

            $id

        );


        if (!mysqli_stmt_execute($stmt)) {

            echo json_encode([

                'success' => false,

                'mensaje' =>
                    mysqli_stmt_error($stmt)

            ]);

            exit;
        }


        mysqli_stmt_close($stmt);


        echo json_encode([

            'success' => true,

            'mensaje' =>
                'Actividad eliminada correctamente.'

        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | ACCIÓN NO VÁLIDA
    |--------------------------------------------------------------------------
    */

    echo json_encode([

        'success' => false,

        'mensaje' =>
            'Acción no válida.'

    ]);

    exit;
}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Panel de Actividades</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

</head>


<body class="bg-light">

<div class="container py-4">
    <h1 class="text-center mb-4">
        Panel de Actividades
    </h1>

    <!-- Resumen -->
    <div class="row mb-4 text-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Actividades</h5>
                    <h2 id="total-actividades">
                        0
                    </h2>
                </div>
            </div>
        </div>


        <div class="col-md-4">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h5>Total Inscripciones</h5>

                    <h2 id="total-inscripciones">
                        0
                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h5>Participación</h5>

                    <h2 id="media-participacion">
                        0%
                    </h2>

                </div>

            </div>

        </div>

    </div>


    <!-- Botones -->

    <div class="mb-3 text-center">


        <button
            class="btn btn-success me-2"
            onclick="abrirModalActividad()"
        >
            + Nueva Actividad
        </button>


        <button
            class="btn btn-primary me-2"
            onclick="abrirModalInscripcion()"
        >
            + Nueva Inscripción
        </button>


        <button
            class="btn btn-warning me-2"
            onclick="exportarPDF()"
        >
            Exportar PDF
        </button>


        <button
            class="btn btn-info"
            onclick="exportarExcel()"
        >
            Exportar Excel
        </button>


    </div>


    <!-- Tabla Actividades -->

    <h3 class="mt-4">
        Lista de Actividades
    </h3>


    <div class="table-responsive">


        <table
            id="tabla-actividades-html"
            class="table table-bordered table-striped"
        >


            <thead class="table-dark">

                <tr>

                    <th>ID</th>

                    <th>Nombre</th>

                    <th>Tipo</th>

                    <th>Fecha</th>

                    <th>Lugar</th>

                    <th>Inscritos</th>

                    <th>Acciones</th>

                </tr>

            </thead>


            <tbody id="tabla-actividades"></tbody>


        </table>


    </div>


    <!-- Tabla Inscripciones -->

    <h3 class="mt-4">
        Lista de Inscripciones
    </h3>


    <div class="table-responsive">


        <table
            id="tabla-inscripciones-html"
            class="table table-bordered table-striped"
        >


            <thead class="table-dark">

                <tr>

                    <th>#</th>

                    <th>Participante</th>

                    <th>Actividad</th>

                    <th>Acciones</th>

                </tr>

            </thead>


            <tbody id="tabla-inscripciones"></tbody>


        </table>


    </div>


    <!-- Gráfico -->

    <h3 class="mt-5">
        Estadísticas
    </h3>


    <canvas
        id="grafico-actividades"
        height="100"
    ></canvas>


</div>



<!-- Modal Actividad -->

<div
    class="modal fade"
    id="actividadModal"
    tabindex="-1"
>


    <div class="modal-dialog">


        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Registrar Actividad
                </h5>

            </div>


            <div class="modal-body">


                <input
                    type="hidden"
                    id="actividadId"
                >


                <div class="mb-3">

                    <label>
                        Nombre
                    </label>

                    <input
                        type="text"
                        id="actividadNombre"
                        class="form-control"
                    >

                </div>


                <div class="mb-3">

                    <label>
                        Tipo
                    </label>

                    <input
                        type="text"
                        id="actividadTipo"
                        class="form-control"
                    >

                </div>


                <div class="mb-3">

                    <label>
                        Fecha
                    </label>

                    <input
                        type="date"
                        id="actividadFecha"
                        class="form-control"
                    >

                </div>


                <div class="mb-3">

                    <label>
                        Lugar
                    </label>

                    <input
                        type="text"
                        id="actividadLugar"
                        class="form-control"
                    >

                </div>


                <!-- DÍAS DE LA SEMANA -->

                <div class="mb-3">

                    <label class="form-label">
                        Días de la semana
                    </label>


                    <div class="row">


                        <div class="col-md-6">

                            <div class="form-check">

                                <input
                                    class="form-check-input dia-semana"
                                    type="checkbox"
                                    value="Lunes"
                                    id="diaLunes"
                                >

                                <label
                                    class="form-check-label"
                                    for="diaLunes"
                                >
                                    Lunes
                                </label>

                            </div>


                            <div class="form-check">

                                <input
                                    class="form-check-input dia-semana"
                                    type="checkbox"
                                    value="Martes"
                                    id="diaMartes"
                                >

                                <label
                                    class="form-check-label"
                                    for="diaMartes"
                                >
                                    Martes
                                </label>

                            </div>


                            <div class="form-check">

                                <input
                                    class="form-check-input dia-semana"
                                    type="checkbox"
                                    value="Miércoles"
                                    id="diaMiercoles"
                                >

                                <label
                                    class="form-check-label"
                                    for="diaMiercoles"
                                >
                                    Miércoles
                                </label>

                            </div>


                            <div class="form-check">

                                <input
                                    class="form-check-input dia-semana"
                                    type="checkbox"
                                    value="Jueves"
                                    id="diaJueves"
                                >

                                <label
                                    class="form-check-label"
                                    for="diaJueves"
                                >
                                    Jueves
                                </label>

                            </div>

                        </div>


                        <div class="col-md-6">


                            <div class="form-check">

                                <input
                                    class="form-check-input dia-semana"
                                    type="checkbox"
                                    value="Viernes"
                                    id="diaViernes"
                                >

                                <label
                                    class="form-check-label"
                                    for="diaViernes"
                                >
                                    Viernes
                                </label>

                            </div>


                            <div class="form-check">

                                <input
                                    class="form-check-input dia-semana"
                                    type="checkbox"
                                    value="Sábado"
                                    id="diaSabado"
                                >

                                <label
                                    class="form-check-label"
                                    for="diaSabado"
                                >
                                    Sábado
                                </label>

                            </div>


                            <div class="form-check">

                                <input
                                    class="form-check-input dia-semana"
                                    type="checkbox"
                                    value="Domingo"
                                    id="diaDomingo"
                                >

                                <label
                                    class="form-check-label"
                                    for="diaDomingo"
                                >
                                    Domingo
                                </label>

                            </div>


                        </div>

                    </div>

                </div>


            </div>


            <div class="modal-footer">


                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancelar
                </button>


                <button
                    class="btn btn-primary"
                    onclick="guardarActividad()"
                >
                    Guardar
                </button>


            </div>


        </div>

    </div>

</div>



<!-- Modal Inscripción -->

<div
    class="modal fade"
    id="inscripcionModal"
    tabindex="-1"
>


    <div class="modal-dialog">


        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Registrar Inscripción
                </h5>

            </div>


            <div class="modal-body">


                <input
                    type="hidden"
                    id="inscripcionId"
                >


                <div class="mb-3">

                    <label>
                        Participante
                    </label>

                    <input
                        type="text"
                        id="inscripcionUsuario"
                        class="form-control"
                    >

                </div>


                <div class="mb-3">

                    <label>
                        Actividad
                    </label>


                    <select
                        id="actividadInscripcion"
                        class="form-select"
                    ></select>


                </div>


            </div>


            <div class="modal-footer">


                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancelar
                </button>


                <button
                    class="btn btn-primary"
                    onclick="guardarInscripcion()"
                >
                    Guardar
                </button>


            </div>


        </div>

    </div>

</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>


<script>

/*
|--------------------------------------------------------------------------
| MODALES
|--------------------------------------------------------------------------
*/

const modalActividad =
    new bootstrap.Modal(
        document.getElementById('actividadModal')
    );


const modalInscripcion =
    new bootstrap.Modal(
        document.getElementById('inscripcionModal')
    );


let actividades = [];


let inscripciones =
    JSON.parse(
        localStorage.getItem("inscripciones")
    ) || [];


let chart;



/*
|--------------------------------------------------------------------------
| CARGAR ACTIVIDADES DESDE MYSQL
|--------------------------------------------------------------------------
*/

async function cargarActividadesBD() {

    try {

        const respuesta =
            await fetch(
                "Panel_Actividades.php?accion=listar"
            );


        const datos =
            await respuesta.json();


        if (!datos.success) {

            alert(
                datos.mensaje
            );

            return;
        }


        actividades =
            datos.actividades;


        cargarActividades();

        cargarResumen();

        cargarInscripciones();


    } catch (error) {

        console.error(error);

        alert(
            "No se pudo conectar con la base de datos."
        );

    }

}



/*
|--------------------------------------------------------------------------
| RESUMEN
|--------------------------------------------------------------------------
*/

function cargarResumen() {

    document.getElementById(
        "total-actividades"
    ).textContent =
        actividades.length;


    document.getElementById(
        "total-inscripciones"
    ).textContent =
        inscripciones.length;


    let media =
        actividades.length > 0

            ? (
                (
                    inscripciones.length /
                    (actividades.length * 10)
                ) * 100
            ).toFixed(1)

            : 0;


    document.getElementById(
        "media-participacion"
    ).textContent =
        media + "%";

}



/*
|--------------------------------------------------------------------------
| TABLA ACTIVIDADES
|--------------------------------------------------------------------------
*/

function cargarActividades() {

    const tabla =
        document.getElementById(
            "tabla-actividades"
        );


    tabla.innerHTML = "";


    const select =
        document.getElementById(
            "actividadInscripcion"
        );


    select.innerHTML =
        "<option value='' disabled selected>Seleccionar...</option>";


    if (actividades.length === 0) {

        tabla.innerHTML =
            "<tr>" +
            "<td colspan='7' class='text-center'>" +
            "No hay actividades registradas" +
            "</td>" +
            "</tr>";

    } else {


        actividades.forEach(
            act => {


                const count =
                    inscripciones.filter(
                        i =>
                            Number(i.actividadId) ===
                            Number(act.id)
                    ).length;


                const tr =
                    document.createElement("tr");


                tr.innerHTML = `

                    <td>${act.id}</td>

                    <td>${escapeHTML(act.nombre)}</td>

                    <td>${escapeHTML(act.tipo)}</td>

                    <td>${act.fecha}</td>

                    <td>${escapeHTML(act.lugar || '')}</td>

                    <td>${count} inscritos</td>

                    <td>

                        <button
                            class="btn btn-sm btn-primary me-1"
                            onclick="editarActividad(${act.id})"
                        >
                            Editar
                        </button>

                        <button
                            class="btn btn-sm btn-danger"
                            onclick="eliminarActividad(${act.id})"
                        >
                            Eliminar
                        </button>

                    </td>

                `;


                tabla.appendChild(tr);


                select.innerHTML += `

                    <option value="${act.id}">
                        ${escapeHTML(act.nombre)}
                    </option>

                `;

            }
        );

    }


    cargarGraficoInscripciones();

}



/*
|--------------------------------------------------------------------------
| MOSTRAR HTML DE FORMA SEGURA
|--------------------------------------------------------------------------
*/

function escapeHTML(text) {

    if (
        text === null ||
        text === undefined
    ) {

        return "";

    }


    const div =
        document.createElement("div");


    div.textContent =
        text;


    return div.innerHTML;

}



/*
|--------------------------------------------------------------------------
| INSCRIPCIONES
|--------------------------------------------------------------------------
*/

function cargarInscripciones() {

    const tabla =
        document.getElementById(
            "tabla-inscripciones"
        );


    tabla.innerHTML = "";


    if (inscripciones.length === 0) {

        tabla.innerHTML =
            "<tr>" +
            "<td colspan='4' class='text-center'>" +
            "No hay inscripciones" +
            "</td>" +
            "</tr>";

        return;

    }


    inscripciones.forEach(
        (ins, index) => {

            const tr =
                document.createElement("tr");


            tr.innerHTML = `

                <td>${index + 1}</td>

                <td>${escapeHTML(ins.usuario)}</td>

                <td>${escapeHTML(ins.actividad)}</td>

                <td>

                    <button
                        class="btn btn-sm btn-danger"
                        onclick="eliminarInscripcion(${ins.id})"
                    >
                        Eliminar
                    </button>

                </td>

            `;


            tabla.appendChild(tr);

        }
    );

}



/*
|--------------------------------------------------------------------------
| GRÁFICO
|--------------------------------------------------------------------------
*/

function cargarGraficoInscripciones() {

    const ctx =
        document
            .getElementById(
                "grafico-actividades"
            )
            .getContext("2d");


    if (chart) {

        chart.destroy();

    }


    const labels =
        actividades.map(
            a => a.nombre
        );


    const data =
        actividades.map(
            a =>
                inscripciones.filter(
                    i =>
                        Number(i.actividadId) ===
                        Number(a.id)
                ).length
        );


    chart =
        new Chart(
            ctx,
            {

                type: "bar",

                data: {

                    labels,

                    datasets: [{

                        label:
                            "Inscripciones por Actividad",

                        data,

                        backgroundColor:
                            "rgba(54, 162, 235, 0.7)"

                    }]

                },

                options: {

                    scales: {

                        y: {

                            beginAtZero: true,

                            ticks: {
                                stepSize: 1
                            }

                        }

                    }

                }

            }
        );

}



/*
|--------------------------------------------------------------------------
| ABRIR MODAL ACTIVIDAD
|--------------------------------------------------------------------------
*/

function abrirModalActividad() {

    document.getElementById(
        "actividadId"
    ).value = "";


    document.getElementById(
        "actividadNombre"
    ).value = "";


    document.getElementById(
        "actividadTipo"
    ).value = "";


    document.getElementById(
        "actividadFecha"
    ).value = "";


    document.getElementById(
        "actividadLugar"
    ).value = "";


    /*
    |--------------------------------------------------------------------------
    | DESMARCAR DÍAS
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            ".dia-semana"
        )
        .forEach(
            checkbox => {
                checkbox.checked = false;
            }
        );


    document.querySelector(
        "#actividadModal .modal-title"
    ).textContent =
        "Registrar Actividad";


    modalActividad.show();

}



/*
|--------------------------------------------------------------------------
| OBTENER DÍAS SELECCIONADOS
|--------------------------------------------------------------------------
*/

function obtenerDiasSeleccionados() {

    const dias = [];


    document
        .querySelectorAll(
            ".dia-semana:checked"
        )
        .forEach(
            checkbox => {

                dias.push(
                    checkbox.value
                );

            }
        );


    return dias;

}



/*
|--------------------------------------------------------------------------
| MARCAR DÍAS AL EDITAR
|--------------------------------------------------------------------------
*/

function marcarDiasSeleccionados(
    dias
) {

    document
        .querySelectorAll(
            ".dia-semana"
        )
        .forEach(
            checkbox => {

                checkbox.checked =
                    dias.includes(
                        checkbox.value
                    );

            }
        );

}



/*
|--------------------------------------------------------------------------
| GUARDAR ACTIVIDAD
|--------------------------------------------------------------------------
*/

async function guardarActividad() {

    const id =
        document.getElementById(
            "actividadId"
        ).value;


    const nombre =
        document.getElementById(
            "actividadNombre"
        ).value.trim();


    const tipo =
        document.getElementById(
            "actividadTipo"
        ).value.trim();


    const fecha =
        document.getElementById(
            "actividadFecha"
        ).value;


    const lugar =
        document.getElementById(
            "actividadLugar"
        ).value.trim();


    const diasSemana =
        obtenerDiasSeleccionados();


    /*
    |--------------------------------------------------------------------------
    | VALIDAR
    |--------------------------------------------------------------------------
    */

    if (
        !nombre ||
        !tipo ||
        !fecha ||
        !lugar ||
        diasSemana.length === 0
    ) {

        alert(
            "Completa todos los campos y selecciona al menos un día."
        );

        return;

    }


    try {

        let url;

        let opciones;


        /*
        |--------------------------------------------------------------------------
        | EDITAR
        |--------------------------------------------------------------------------
        */

        if (id) {

            url =
                "Panel_Actividades.php?accion=editar";


            opciones = {

                method: "POST",

                headers: {

                    "Content-Type":
                        "application/json"

                },

                body:
                    JSON.stringify({

                        id: id,

                        nombre: nombre,

                        tipo: tipo,

                        fecha: fecha,

                        lugar: lugar,

                        dias_semana:
                            diasSemana

                    })

            };

        }


        /*
        |--------------------------------------------------------------------------
        | NUEVO
        |--------------------------------------------------------------------------
        */

        else {

            url =
                "Panel_Actividades.php?accion=guardar";


            opciones = {

                method: "POST",

                headers: {

                    "Content-Type":
                        "application/json"

                },

                body:
                    JSON.stringify({

                        nombre: nombre,

                        tipo: tipo,

                        fecha: fecha,

                        lugar: lugar,

                        dias_semana:
                            diasSemana

                    })

            };

        }


        const respuesta =
            await fetch(
                url,
                opciones
            );


        const datos =
            await respuesta.json();


        if (!datos.success) {

            alert(
                datos.mensaje
            );

            return;

        }


        alert(
            datos.mensaje
        );


        modalActividad.hide();


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR DESDE MYSQL
        |--------------------------------------------------------------------------
        */

        await cargarActividadesBD();


    } catch (error) {

        console.error(error);

        alert(
            "Ocurrió un error al guardar la actividad."
        );

    }

}



/*
|--------------------------------------------------------------------------
| EDITAR ACTIVIDAD
|--------------------------------------------------------------------------
*/

function editarActividad(id) {

    const act =
        actividades.find(
            a =>
                Number(a.id) ===
                Number(id)
        );


    if (!act) {

        alert(
            "No se encontró la actividad."
        );

        return;

    }


    document.getElementById(
        "actividadId"
    ).value =
        act.id;


    document.getElementById(
        "actividadNombre"
    ).value =
        act.nombre;


    document.getElementById(
        "actividadTipo"
    ).value =
        act.tipo;


    document.getElementById(
        "actividadFecha"
    ).value =
        act.fecha;


    document.getElementById(
        "actividadLugar"
    ).value =
        act.lugar || "";


    /*
    |--------------------------------------------------------------------------
    | RECUPERAR DÍAS
    |--------------------------------------------------------------------------
    */

    const dias =
        act.dias_semana
            ? act.dias_semana.split(",")
            : [];


    marcarDiasSeleccionados(
        dias
    );


    document.querySelector(
        "#actividadModal .modal-title"
    ).textContent =
        "Editar Actividad";


    modalActividad.show();

}



/*
|--------------------------------------------------------------------------
| ELIMINAR ACTIVIDAD
|--------------------------------------------------------------------------
*/

async function eliminarActividad(id) {

    if (
        !confirm(
            "¿Eliminar actividad?"
        )
    ) {

        return;

    }


    try {

        const respuesta =
            await fetch(

                "Panel_Actividades.php?accion=eliminar&id=" +
                encodeURIComponent(id)

            );


        const datos =
            await respuesta.json();


        if (!datos.success) {

            alert(
                datos.mensaje
            );

            return;

        }


        alert(
            datos.mensaje
        );


        await cargarActividadesBD();


    } catch (error) {

        console.error(error);

        alert(
            "No se pudo eliminar la actividad."
        );

    }

}



/*
|--------------------------------------------------------------------------
| MODAL INSCRIPCIÓN
|--------------------------------------------------------------------------
*/

function abrirModalInscripcion() {

    document.getElementById(
        "inscripcionId"
    ).value = "";


    document.getElementById(
        "inscripcionUsuario"
    ).value = "";


    modalInscripcion.show();

}



/*
|--------------------------------------------------------------------------
| GUARDAR INSCRIPCIÓN
|--------------------------------------------------------------------------
*/

function guardarInscripcion() {

    const usuario =
        document.getElementById(
            "inscripcionUsuario"
        ).value.trim();


    const actividadId =
        parseInt(
            document.getElementById(
                "actividadInscripcion"
            ).value
        );


    const actividad =
        actividades.find(
            a =>
                Number(a.id) ===
                Number(actividadId)
        );


    if (
        !usuario ||
        !actividadId ||
        !actividad
    ) {

        alert(
            "Completa todos los campos"
        );

        return;

    }


    const nuevoId =
        inscripciones.length > 0

            ? Math.max(
                ...inscripciones.map(
                    i => i.id
                )
            ) + 1

            : 1;


    inscripciones.push({

        id: nuevoId,

        usuario: usuario,

        actividad:
            actividad.nombre,

        actividadId:
            actividad.id

    });


    localStorage.setItem(

        "inscripciones",

        JSON.stringify(
            inscripciones
        )

    );


    cargarActividades();

    cargarInscripciones();

    cargarResumen();


    modalInscripcion.hide();

}



/*
|--------------------------------------------------------------------------
| ELIMINAR INSCRIPCIÓN
|--------------------------------------------------------------------------
*/

function eliminarInscripcion(id) {

    if (
        !confirm(
            "¿Eliminar inscripción?"
        )
    ) {

        return;

    }


    inscripciones =
        inscripciones.filter(
            i =>
                i.id != id
        );


    localStorage.setItem(

        "inscripciones",

        JSON.stringify(
            inscripciones
        )

    );


    cargarActividades();

    cargarInscripciones();

    cargarResumen();

}



/*
|--------------------------------------------------------------------------
| EXPORTAR PDF
|--------------------------------------------------------------------------
*/

function exportarPDF() {

    const {
        jsPDF
    } = window.jspdf;


    const doc =
        new jsPDF();


    doc.text(
        "Panel de Actividades",
        14,
        15
    );


    const filas =
        actividades.map(
            act => [

                act.id,

                act.nombre,

                act.tipo,

                act.fecha,

                act.lugar,

                inscripciones.filter(
                    i =>
                        Number(i.actividadId) ===
                        Number(act.id)
                ).length

            ]
        );


    doc.autoTable({

        head: [[

            "ID",

            "Nombre",

            "Tipo",

            "Fecha",

            "Lugar",

            "Inscritos"

        ]],

        body: filas,

        startY: 25

    });


    doc.save(
        "actividades.pdf"
    );

}



/*
|--------------------------------------------------------------------------
| EXPORTAR EXCEL
|--------------------------------------------------------------------------
*/

function exportarExcel() {

    const datos =
        actividades.map(
            act => ({

                ID:
                    act.id,

                Nombre:
                    act.nombre,

                Tipo:
                    act.tipo,

                Fecha:
                    act.fecha,

                Lugar:
                    act.lugar,

                "Días de la semana":
                    act.dias_semana,

                Inscritos:
                    inscripciones.filter(
                        i =>
                            Number(i.actividadId) ===
                            Number(act.id)
                    ).length

            })
        );


    const hoja =
        XLSX.utils.json_to_sheet(
            datos
        );


    const libro =
        XLSX.utils.book_new();


    XLSX.utils.book_append_sheet(

        libro,

        hoja,

        "Actividades"

    );


    XLSX.writeFile(

        libro,

        "actividades.xlsx"

    );

}



/*
|--------------------------------------------------------------------------
| INICIALIZAR
|--------------------------------------------------------------------------
*/

cargarActividadesBD();



/*
|--------------------------------------------------------------------------
| ACTUALIZACIÓN AUTOMÁTICA
|--------------------------------------------------------------------------
|
| Consulta nuevamente MySQL cada 5 segundos.
|
*/

setInterval(

    cargarActividadesBD,

    5000

);

</script>


</body>

</html>