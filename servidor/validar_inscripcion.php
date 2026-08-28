<?php

session_start();

include("conexionBD.php");

// ==========================================================
// 1. RECIBIR DATOS
// ==========================================================

$id_actividad = isset($_POST['id_actividad'])
    ? (int) $_POST['id_actividad']
    : 0;

$nombre = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$ci = trim($_POST['ci'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$observacion = trim($_POST['observacion'] ?? '');


// ==========================================================
// 2. VALIDAR DATOS
// ==========================================================

if ($id_actividad <= 0) {
    die("Actividad no válida.");
}

if (
    $nombre === '' ||
    $apellidos === '' ||
    $correo === '' ||
    $ci === ''
) {
    die("Complete todos los campos obligatorios.");
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("El correo electrónico no es válido.");
}


// ==========================================================
// 3. VERIFICAR ACTIVIDAD
// ==========================================================

$sqlActividad = "
    SELECT
        id_actividad,
        nombre_actividad,
        cupo_disponible,
        costo,
        estado
    FROM actividades
    WHERE id_actividad = ?
    LIMIT 1
";

$stmtActividad = $conexion->prepare($sqlActividad);

if (!$stmtActividad) {
    die("Error al preparar actividad: " . $conexion->error);
}

$stmtActividad->bind_param(
    "i",
    $id_actividad
);

$stmtActividad->execute();

$resultadoActividad = $stmtActividad->get_result();

$actividad = $resultadoActividad->fetch_assoc();

$stmtActividad->close();


// ==========================================================
// 4. COMPROBAR ACTIVIDAD
// ==========================================================

if (!$actividad) {
    die("La actividad no existe.");
}

if ($actividad['estado'] !== 'Activo') {
    die("La actividad no está disponible.");
}


// ==========================================================
// 5. VERIFICAR CUPOS
// ==========================================================

$cupoDisponible = (int) $actividad['cupo_disponible'];

if ($cupoDisponible <= 0) {

    echo "<script>
        alert('Lo sentimos, ya no existen cupos disponibles.');
        window.location='../cliente/Ver_Actividad.php?id=$id_actividad';
    </script>";

    exit;
}


// ==========================================================
// 6. BUSCAR PERSONA POR CI
// ==========================================================

$sqlPersonaCI = "
    SELECT
        id_persona,
        nombres,
        apellidos,
        ci,
        correo,
        telefono
    FROM personas
    WHERE ci = ?
    LIMIT 1
";

$stmtPersonaCI = $conexion->prepare($sqlPersonaCI);

if (!$stmtPersonaCI) {
    die(
        "Error al buscar persona por CI: "
        . $conexion->error
    );
}

$stmtPersonaCI->bind_param(
    "s",
    $ci
);

$stmtPersonaCI->execute();

$resultadoCI = $stmtPersonaCI->get_result();

$personaPorCI = $resultadoCI->fetch_assoc();

$stmtPersonaCI->close();


// ==========================================================
// 7. SI EXISTE POR CI
// ==========================================================

if ($personaPorCI) {

    $id_persona = (int) $personaPorCI['id_persona'];

}


// ==========================================================
// 8. SI NO EXISTE POR CI, BUSCAR POR CORREO
// ==========================================================

else {

    $sqlPersonaCorreo = "
        SELECT
            id_persona,
            nombres,
            apellidos,
            ci,
            correo,
            telefono
        FROM personas
        WHERE correo = ?
        LIMIT 1
    ";

    $stmtPersonaCorreo =
        $conexion->prepare($sqlPersonaCorreo);

    if (!$stmtPersonaCorreo) {
        die(
            "Error al buscar persona por correo: "
            . $conexion->error
        );
    }

    $stmtPersonaCorreo->bind_param(
        "s",
        $correo
    );

    $stmtPersonaCorreo->execute();

    $resultadoCorreo =
        $stmtPersonaCorreo->get_result();

    $personaPorCorreo =
        $resultadoCorreo->fetch_assoc();

    $stmtPersonaCorreo->close();


    // ======================================================
    // 9. EXISTE POR CORREO
    // ======================================================

    if ($personaPorCorreo) {

        $id_persona =
            (int) $personaPorCorreo['id_persona'];

    }


    // ======================================================
    // 10. NO EXISTE NI POR CI NI POR CORREO
    // ======================================================

    else {

        /*
         * La tabla personas tiene password obligatorio.
         *
         * Generamos una contraseña temporal.
         * Se guarda cifrada.
         */

        $passwordTemporal =
            bin2hex(random_bytes(8));

        $passwordHash =
            password_hash(
                $passwordTemporal,
                PASSWORD_DEFAULT
            );


        $sqlInsertPersona = "
            INSERT INTO personas
            (
                nombres,
                apellidos,
                ci,
                correo,
                telefono,
                password
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $stmtInsertPersona =
            $conexion->prepare(
                $sqlInsertPersona
            );

        if (!$stmtInsertPersona) {
            die(
                "Error al preparar registro de persona: "
                . $conexion->error
            );
        }

        $stmtInsertPersona->bind_param(
            "ssssss",
            $nombre,
            $apellidos,
            $ci,
            $correo,
            $telefono,
            $passwordHash
        );


        if (!$stmtInsertPersona->execute()) {

            die(
                "Error al registrar persona: "
                . $stmtInsertPersona->error
            );
        }


        $id_persona =
            $conexion->insert_id;

        $stmtInsertPersona->close();
    }
}


// ==========================================================
// 11. VERIFICAR INSCRIPCIÓN DUPLICADA
// ==========================================================

$sqlExiste = "
    SELECT
        id_inscripcion
    FROM inscripcion
    WHERE id_actividad = ?
      AND id_persona = ?
    LIMIT 1
";

$stmtExiste =
    $conexion->prepare($sqlExiste);

if (!$stmtExiste) {
    die(
        "Error al verificar inscripción: "
        . $conexion->error
    );
}

$stmtExiste->bind_param(
    "ii",
    $id_actividad,
    $id_persona
);

$stmtExiste->execute();

$resultadoExiste =
    $stmtExiste->get_result();

$inscripcionExistente =
    $resultadoExiste->fetch_assoc();

$stmtExiste->close();


// ==========================================================
// 12. SI YA ESTÁ INSCRITO
// ==========================================================

if ($inscripcionExistente) {

    echo "<script>

        alert('Ya estás inscrito en esta actividad.');

        window.location='../cliente/Ver_Actividades.php';

    </script>";

    exit;
}


// ==========================================================
// 13. DATOS DE INSCRIPCIÓN
// ==========================================================

$estado = 'Inscrito';

$cumple_requisitos = 'Si';

$asistencia = 1;

$calificacion = null;

$fecha_inscripcion =
    date('Y-m-d H:i:s');

$fecha_actualizacion =
    date('Y-m-d H:i:s');


// ==========================================================
// 14. PAGO
// ==========================================================

/*
 * El pago se registrará posteriormente.
 * Por eso inicialmente queda NULL.
 */

$id_pago = null;


// ==========================================================
// 15. INSERTAR INSCRIPCIÓN
// ==========================================================

$sqlInscripcion = "
    INSERT INTO inscripcion
    (
        id_actividad,
        id_persona,
        id_pago,
        cumple_requisitos,
        estado,
        fecha_inscripcion,
        fecha_actualizacion,
        observaciones,
        asistencia,
        calificacion
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
";

$stmtInscripcion =
    $conexion->prepare(
        $sqlInscripcion
    );

if (!$stmtInscripcion) {
    die(
        "Error al preparar inscripción: "
        . $conexion->error
    );
}


// ==========================================================
// 16. ASIGNAR VALORES
// ==========================================================

$stmtInscripcion->bind_param(
    "iiisssssis",
    $id_actividad,
    $id_persona,
    $id_pago,
    $cumple_requisitos,
    $estado,
    $fecha_inscripcion,
    $fecha_actualizacion,
    $observacion,
    $asistencia,
    $calificacion
);


// ==========================================================
// 17. EJECUTAR INSCRIPCIÓN
// ==========================================================

if (!$stmtInscripcion->execute()) {

    echo "Error al registrar inscripción: "
        . $stmtInscripcion->error;

    $stmtInscripcion->close();

    exit;
}

$stmtInscripcion->close();


// ==========================================================
// 18. REDUCIR CUPO
// ==========================================================

$sqlCupo = "
    UPDATE actividades
    SET cupo_disponible = cupo_disponible - 1
    WHERE id_actividad = ?
      AND cupo_disponible > 0
";

$stmtCupo =
    $conexion->prepare($sqlCupo);

if ($stmtCupo) {

    $stmtCupo->bind_param(
        "i",
        $id_actividad
    );

    $stmtCupo->execute();

    $stmtCupo->close();
}


// ==========================================================
// 19. FINALIZAR
// ==========================================================

echo "<script>

    alert('¡Inscripción registrada correctamente!');

    window.location='../cliente/Ver_Actividades.php;

</script>";

exit;

?>