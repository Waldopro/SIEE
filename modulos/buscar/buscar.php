<?php
function buscarRegistro($criterios, $tabla, $conexion) {
    $filtros = [];
    foreach ($criterios as $campo => $valor) {
        $valor_escapado = $conexion->real_escape_string($valor);
        $filtros[] = "$campo = '$valor_escapado'";
    }
    $condiciones = implode(' AND ', $filtros);

    $query = "SELECT * FROM $tabla WHERE $condiciones LIMIT 1";
    $resultado = $conexion->query($query);

    if ($resultado && $resultado->num_rows > 0) {
        return $resultado->fetch_assoc();
    }
    return null;
}
?>
