<?php

function insertarRepresentante($rep, $conexion) {
    if (empty($rep) || !is_array($rep)) {
        return false;
    }

    // Asegurar valores vacíos convertidos a 0 si es necesario
    foreach ($rep as $key => $value) {
        if ($value === '' && in_array($key, ['edad', 'ser_car', 'codigo_car', 'tipo_cta', 'num_cuenta'])) {
            $rep[$key] = 0;
        }
    }

    $columnas = array_keys($rep);
    $placeholders = array_fill(0, count($columnas), '?');
    $valores = array_values($rep);

    $sql_rep = 'INSERT INTO representante (' . implode(', ', $columnas) . ') VALUES (' . implode(', ', $placeholders) . ')';
    $stmt = $conexion->prepare($sql_rep);

    if ($stmt === false) {
        error_log("Error en preparación de consulta representante: " . $conexion->error);
        return false;
    }

    $tipos = str_repeat('s', count($valores));
    $stmt->bind_param($tipos, ...$valores);

    if (!$stmt->execute()) {
        error_log("Error al insertar representante: " . $stmt->error);
        return false;
    }

    return $conexion->insert_id;
}


function insertarPadreMadre($pad, $conexion) {
    if (!is_array($pad) || empty($pad)) {
        return false;
    }

    // Verificar que las claves sean cadenas (nombres de columnas)
    foreach (array_keys($pad) as $key) {
        if (!is_string($key)) {
            return false;
        }
    }

    $columnas = array_keys($pad);
    $placeholders = array_fill(0, count($columnas), '?');
    $valores = array_values($pad);

    $sql_pad = 'INSERT INTO padre_madre (' . implode(', ', $columnas) . ') VALUES (' . implode(', ', $placeholders) . ')';

    $stmt = $conexion->prepare($sql_pad);
    if ($stmt === false) {
        error_log("Error en preparación de consulta padre_madre: " . $conexion->error);
        return false;
    }

    $tipos = str_repeat('s', count($valores));
    $stmt->bind_param($tipos, ...$valores);

    if (!$stmt->execute()) {
        error_log("Error al insertar padre/madre: " . $stmt->error);
        return false;
    }

    return $conexion->insert_id;
}


// Función para insertar un estudiante en la base de datos
function insertarEstudiante($est, $conexion) {
    $sql = 'INSERT INTO estudiante (' . implode(', ', array_keys($est)) . ') VALUES (' . implode(', ', array_fill(0, count($est), '?')) . ')';
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($est)), ...array_values($est));

    if (!$stmt->execute()) {
        error_log("Error al insertar estudiante: " . $stmt->error);
        return false;
    }
    return $conexion->insert_id;
}

// Función para insertar inscripción en la base de datos
function insertarInscripcion($ins, $conexion) {
    $sql = 'INSERT INTO inscripcion (' . implode(', ', array_keys($ins)) . ') VALUES (' . implode(', ', array_fill(0, count($ins), '?')) . ')';
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($ins)), ...array_values($ins));

    if (!$stmt->execute()) {
        error_log("Error al insertar inscripción: " . $stmt->error);
        return false;
    }
    return $conexion->insert_id;
}

// Función para insertar un padre o madre utilizando los datos de un representante
function insertarPadreMadreConDatosRepresentante($rep, $conexion) {
    $arreglo = [
        'cedula_p' => $rep['cedula_r'],
        'apellidos_p' => $rep['apellidos'],
        'nombres_p' => $rep['nombres'],
        'fecha_nac_p' => $rep['fecha_nacimiento'],
        'edad' => $rep['edad'],
        'ocupacion_p' => $rep['ocupacion'],
        'tel_p' => $rep['tel_r'],
        'direccion_p' => $rep['direccion_r'],
        'parentesco' => $rep['parentesco']
    ];
    return insertarPadreMadre($arreglo, $conexion);
}

// Función para buscar un curso en la base de datos
function BuscarCurso($busqueda, $conexion) {
    $sql = "SELECT * FROM curso WHERE id_grado = ? AND id_seccion = ? AND anho = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $id_grado = $busqueda['id_grado'];
        $id_seccion = $busqueda['id_seccion'];
        $anho = $busqueda['anho'];

        $stmt->bind_param("iii", $id_grado, $id_seccion, $anho);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $stmt->close();
            return $rows;
        } else {
            $stmt->close();
            return [];
        }
    } else {
        error_log("Error en consulta BuscarCurso: " . $conexion->error);
        return false;
    }
}

?>	
