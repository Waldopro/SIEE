<?php
include '../error.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include '../verificar_session.php';
include '../conexion/conndb.php';

if (isset($_POST['update'])) {
    // Recibir y sanitizar los datos del formulario
    $id_estudiante = $conexion->real_escape_string($_POST['id_estudiante']);
    $apellidos_e = $conexion->real_escape_string($_POST['apellidos_e']);
    $nombres_e = $conexion->real_escape_string($_POST['nombres_e']);
    $fecha_nac = $conexion->real_escape_string($_POST['fecha_nac']);
    $edad_e = $conexion->real_escape_string($_POST['edad_e']);
    $sexo = $conexion->real_escape_string($_POST['sexo']);
    $peso = $conexion->real_escape_string($_POST['peso']);
    $talla = $conexion->real_escape_string($_POST['talla']);
    $lugar_nac = $conexion->real_escape_string($_POST['lugar_nac']);
    $municipio = $conexion->real_escape_string($_POST['municipio']);
    $estado = $conexion->real_escape_string($_POST['estado']);
    $parroquia = $conexion->real_escape_string($_POST['parroquia']);
    $direccion_e = $conexion->real_escape_string($_POST['direccion_e']);
    $tel_e = $conexion->real_escape_string($_POST['tel_e']);
    $posee_can = $conexion->real_escape_string($_POST['posee_can']);
    $serial_can = $conexion->real_escape_string($_POST['serial_can']);
    $becado = $conexion->real_escape_string($_POST['becado']);

    // Procesar la foto si se envió
    $foto = null;
    if (!empty($_FILES['foto']['tmp_name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto_name = uniqid() . '_' . basename($_FILES['foto']['name']);
        $upload_dir = '../uploads/estudiantes/';

        // Crear el directorio si no existe
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $foto_path = $upload_dir . $foto_name;

        // Validar el formato de la imagen
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            echo "Formato de archivo no permitido.";
            exit();
        }

        // Mover la nueva foto al directorio
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $foto_path)) {
            $foto = $foto_name;

            // Eliminar la foto anterior si existe
            $query_foto_anterior = "SELECT foto FROM familia WHERE id_estudiante = '$id_estudiante'";
            $result_foto_anterior = $conexion->query($query_foto_anterior);
            if ($result_foto_anterior && $result_foto_anterior->num_rows > 0) {
                $row_foto = $result_foto_anterior->fetch_assoc();
                $foto_anterior = $row_foto['foto'];
                $foto_anterior_path = $upload_dir . $foto_anterior;

                if (!empty($foto_anterior) && file_exists($foto_anterior_path)) {
                    unlink($foto_anterior_path);
                }
            }

            // Actualizar la base de datos con la nueva foto
            $update_foto_query = "UPDATE familia SET foto = '$foto' WHERE id_estudiante = '$id_estudiante'";
            if (!$conexion->query($update_foto_query)) {
                echo "Error al actualizar la foto: " . $conexion->error;
                $conexion->close();
                exit();
            }
        } else {
            echo "Error al subir la foto.";
            $conexion->close();
            exit();
        }
    }

    // Actualizar los datos del estudiante
    $sql = "UPDATE estudiante SET 
        apellidos_e='$apellidos_e',
        nombres_e='$nombres_e',
        fecha_nac='$fecha_nac',
        edad_e='$edad_e',
        sexo='$sexo',
        peso='$peso',
        talla='$talla',
        lugar_nac='$lugar_nac',
        municipio='$municipio',
        estado='$estado',
        parroquia='$parroquia',
        direccion_e='$direccion_e',
        tel_e='$tel_e',
        posee_can='$posee_can',
        serial_can='$serial_can',
        becado='$becado'
    WHERE id_estudiante='$id_estudiante'";

    if ($conexion->query($sql) === TRUE) {
        $conexion->close();
        header("Location: ../procesar/procesare.php?status=success");
        exit();
    } else {
        echo "Error al actualizar los datos: " . $conexion->error;
    }

    $conexion->close();
}

// Obtener los datos del estudiante
if (isset($_GET['id'])) {
    $id_estudiante = $conexion->real_escape_string($_GET['id']);
    $sql = "SELECT e.*, f.foto FROM estudiante e 
            LEFT JOIN familia f ON e.id_estudiante = f.id_estudiante 
            WHERE e.id_estudiante = '$id_estudiante'";
    $result = $conexion->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Estudiante no encontrado.";
        $conexion->close();
        exit();
    }
} else {
    echo "No se recibió el parámetro 'id' en la URL.";
    exit();
}
?>


	
<!DOCTYPE html>
<html lang="es">
	
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Editar Datos Del Estudiante</title>
	<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../../css/editare.css">
    <link rel="stylesheet" type="text/css" href="../../css/registro_e.css">
	<link rel="stylesheet" href="../../css/footer.css">
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
</head>

<body oncontextmenu="return false;">
	
	<?php include('../menu.php') ?>
<center><h2 class="h1">Editar Estudiante</h2></center>
	<form id="form" action="editare.php" method="post">
		<div class="contenedor-formularios">
			<div class="formulario">
				<h1 class="animate__animated animate__backInLeft">Datos del Alumno</h1>

				<input type="hidden" name="id_estudiante" value="<?php echo $id_estudiante; ?>"readonly>

				<label>Cédula:</label>
				<input type="number" name="cedula_e" value="<?php echo $row['cedula_e']; ?>" disabled>

				<label>Apellidos:</label>
   			<input type="text" name="apellidos_e" value="<?php echo ucwords(strtolower($row['apellidos_e'])); ?>" style="text-transform: capitalize;" required>
				<script>
					document.addEventListener('DOMContentLoaded', function() {
						const nombresInput = document.querySelector('input[name="nombres_e"]');
						const apellidosInput = document.querySelector('input[name="apellidos_e"]');

						function correctText(input) {
							const corrections = {
								'nino': 'niño',
								'nina': 'niña',
								'senor': 'señor',
								'senora': 'señora',
								'arbol': 'árbol',
								'camion': 'camión',
								'corazon': 'corazón',
								'dia': 'día',
								'pais': 'país',
								'telefono': 'teléfono'
							};

							let words = input.value.split(' ');
							words = words.map(word => corrections[word.toLowerCase()] || word);
							input.value = words.join(' ');
						}

						nombresInput.addEventListener('blur', function() {
							correctText(nombresInput);
						});

						apellidosInput.addEventListener('blur', function() {
							correctText(apellidosInput);
						});
					});
				</script>
				<label>Nombres:</label>
				<input type="text" name="nombres_e" value="<?php echo $row['nombres_e']; ?>" required>
				<label>Fecha de Nacimiento:</label>
<input type="date" name="fecha_nac" value="<?php echo date('Y-m-d', strtotime($row['fecha_nac'])); ?>" required>
<label>Edad:</label>
<input id="edad" name="edad_e" type="number" inputmode="numeric" pattern="\d{1,2}" maxlength="2" value="<?php echo $row['edad_e']; ?>" required readonly><br><br>
				<label>Sexo:</label>
				<select name="sexo" required>
					<option value="Masculino" <?php if (isset($sexo) && $sexo == 'Masculino') {
													echo 'selected';
												} ?>>Masculino</option>
					<option value="Femenino" <?php if (isset($sexo) && $sexo == 'Femenino') {
													echo 'selected';
												} ?>>Femenino</option>
				</select>
				<label>Lugar de Nacimiento:</label>
				<input type="text" name="lugar_nac" value="<?php echo $row['lugar_nac']; ?>" required>

				<label>Estado:</label>
				<select name="estado" id="estado" required>
				
				</select>

				<label>Municipio:</label>
				<select name="municipio" id="municipio" required>
				    
				</select>

				<label>Parroquia:</label>
				<select name="parroquia" id="parroquia" required>
				    
				</select>

				<label>Dirección:</label>
				<input type="text" name="direccion_e" value="<?php echo $row['direccion_e']; ?>" required>
			</div>

			<div class="formulario">
				
					<h1 class="animate__animated animate__backInLeft">Información Adicional</h1>
					<label>Teléfono:</label>
					<input type="tel" id="tel_e" name="tel_e" value="<?php echo $row['tel_e']; ?>" required>
					<label>Peso:</label>
					<input type="text" name="peso" value="<?php echo $row['peso']; ?>" required>
					<label for="talla">Talla:</label>
					<select name="talla" required>
						<option value="SS" <?php if ($row['talla'] == 'SS') echo 'selected'; ?>>SS</option>
						<option value="S" <?php if ($row['talla'] == 'S') echo 'selected'; ?>>S</option>
						<option value="M" <?php if ($row['talla'] == 'M') echo 'selected'; ?>>M</option>
						<option value="L" <?php if ($row['talla'] == 'L') echo 'selected'; ?>>L</option>
					</select>
					<label>Posee Canaima:</label>
					<select name="posee_can" id="posee_can" required>
					<option value="Seleccione"<?php if ($row['posee_can'] == 'Seleccione') echo 'selected'; ?>>Seleccione</option>
						<option value="Si" <?php if ($row['posee_can'] == 'Si') {
												echo 'selected';
											} ?>>Si</option>
						<option value="No" <?php if ($row['posee_can'] == 'No') {
												echo 'selected';
											} ?>>No</option>
					</select>
					<label for="serial_can">Serial del Canaima:</label>
					<input type="text" id="serial_can" name="serial_can" value="<?php echo $row['serial_can']; ?>">
					<span id="no_canaima_text" style="display: none;">No posee Canaima</span>

					<label>Becado:</label>
					<select name="becado" required>
						<option value="Si" <?php if ($row['becado'] == 'Si') {
												echo 'selected';
											} ?>>Si</option>
						<option value="No" <?php if ($row['becado']  == 'No') {
												echo 'selected';
											} ?>>No</option>

					</select>

					  <!-- Campo para subir nueva foto -->
                      <label for="foto" class="custom-label">
        <img src="../../img/foto.png" alt="Foto" class="foto-icon" style="width: 40px; height: 50px;">
        Subir foto
    </label>
<input type="file" name="foto" id="foto" accept="image/*" onchange="previewImage(event)">
<br>
<img id="imagePreview" 
     src="<?php echo !empty($row['foto']) ? '../uploads/estudiantes/' . $row['foto'] : '../uploads/default.png'; ?>" 
     alt="Previsualización de la foto" 
     height="80" 
     width="80" >

					
			</div>
			<div class="formulario">
				
					<h1 class="animate__animated animate__backInLeft">Documentos Consignados</h1>
					<label for="fotos">Fotos tipo Carnet:</label>
					<select id="fotos" name="fotos" required>
						<option value="Si" <?php if ($row['fotos'] == 'Si') echo ' selected'; ?>>Si</option>
						<option value="No" <?php if ($row['fotos'] == 'No') echo ' selected'; ?>>No</option>
					</select><br>
					<label for="copias_ci">Copias de Cedula:</label>
					<select id="copias_ci" name="copias_ci" required>
						<option value="">--Seleccione--</option>
						<option value="Si" <?php if ($row['copias_ci'] == 'Si') echo ' selected'; ?>>Si</option>
						<option value="No" <?php if ($row['copias_ci'] == 'No') echo ' selected'; ?>>No</option>
					</select><br>
					<label for="partida_nac_co">Partida de Nacimiento Copia:</label>
					<select id="partida_nac_co" name="partida_nac_co" required>
						<option value="">--Seleccione--</option>
						<option value="Si" <?php if ($row['partida_nac_co'] == 'Si') echo ' selected'; ?>>Si</option>
						<option value="No" <?php if ($row['partida_nac_co'] == 'No') echo ' selected'; ?>>No</option>
					</select><br>
					<label for="historia_esc">Historia Escolar:</label>
					<select id="historia_esc" name="historia_esc" required>
						<option value="">--Seleccione--</option>
						<option value="Si" <?php if ($row['historia_esc'] == 'Si') echo ' selected'; ?>>Si</option>
						<option value="No" <?php if ($row['historia_esc'] == 'No') echo ' selected'; ?>>No</option>
					</select><br>
					<label for="cert_prom">Certificado de Promoción:</label>
					<select id="cert_prom" name="cert_prom" required>
						<option value="">--Seleccione--</option>
						<option value="Si" <?php if ($row['cert_prom'] == 'Si') echo ' selected'; ?>>Si</option>
						<option value="No" <?php if ($row['cert_prom'] == 'No') echo ' selected'; ?>>No</option>
					</select><br>
					<label for="tarj_vac">Tarjeta de vacunas:</label>
					<select id="tarj_vac" name="tarj_vac" required>
						<option value="">--Seleccione--</option>
						<option value="Si" <?php if ($row['tarj_vac'] == 'Si') echo ' selected'; ?>>Si</option>
						<option value="No" <?php if ($row['tarj_vac'] == 'No') echo ' selected'; ?>>No</option>
					</select><br>
					<label for="cons_conduc">Constancia de Buena Conducta:</label>
					<select id="cons_conduc" name="cons_conduc" required>
						<option value="">--Seleccione--</option>
						<option value="Si" <?php if ($row['cons_conduc'] == 'Si') echo ' selected'; ?>>Si</option>
						<option value="No" <?php if ($row['cons_conduc'] == 'No') echo ' selected'; ?>>No</option>
					</select><br>
					<label for="cons_retiro">Constancia de Retiro:</label>
					<select id="cons_retiro" name="cons_retiro" required>
						<option value="">--Seleccione--</option>
						<option value="Si" <?php if ($row['cons_retiro'] == 'Si') echo ' selected'; ?>>Si</option>
						<option value="No" <?php if ($row['cons_retiro'] == 'No') echo ' selected'; ?>>No</option>
					</select><br>
					<label for="cert_notas">Certificado de Notas:</label>
					<select id="cert_notas" name="cert_notas" required>
						<option value="">--Seleccione--</option>
						<option value="Si" <?php if ($row['cert_notas'] == 'Si') echo ' selected'; ?>>Si</option>
						<option value="No" <?php if ($row['cert_notas'] == 'No') echo ' selected'; ?>>No</option>
					</select><br>

					<input class="btn btn-primary btn-actualizar" type='submit' name="update" value="Actualizar">
                    <style>
  .btn-actualizar {
    background-image: url('../../img/actualizar.png');
    background-repeat: no-repeat;
    background-position: left center;
    background-size: 40px 50px; /* Ajusta el tamaño de la imagen */
    padding-left: 30px; /* Espacio para el texto */
    height: 40px;
  }
</style>
					<a href="../procesar/procesare.php" class="back-button" style="display: inline-block; background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px;"> <img src="../../img/volver1.png" alt="Volver" style="width: 40px; height: 50px;">Volver</a>

				</div>
										</div>
	</form>	
	<div class="footer">
    <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
</div> 
<script type="text/javascript" src="../../lib/venezuela.js"></script>
<script>
	function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('imagePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
	var poseeCanSelect = document.getElementById("posee_can");
var serialCanInput = document.getElementById("serial_can");
var noCanaimaText = document.getElementById("no_canaima_text");

poseeCanSelect.addEventListener("change", function() {
    if (poseeCanSelect.value === "No") {
        serialCanInput.style.display = "none";
        serialCanInput.removeAttribute("required");
        noCanaimaText.style.display = "inline"; // Muestra el texto "No posee Canaima"
    } else {
        serialCanInput.style.display = "block";
        serialCanInput.setAttribute("required", "");
        noCanaimaText.style.display = "none"; // Oculta el texto "No posee Canaima"
    }
});
	document.getElementById('tel_e').addEventListener('input', function (e) {
  e.target.value = e.target.value.replace(/[^\d]/g, '');
});
window.onload = function() {
    var fechaNacField = document.querySelector('input[name="fecha_nac"]');
    var edadField = document.querySelector('input[name="edad_e"]');

    // Calcular la edad cuando se cambia la fecha de nacimiento
    fechaNacField.addEventListener('change', function() {
        var fechaNac = new Date(fechaNacField.value);
        var edad = calcularEdad(fechaNac);
        edadField.value = edad;
    });

    // Calcular la edad inicial
    if (fechaNacField.value) {
        var fechaNac = new Date(fechaNacField.value);
        var edad = calcularEdad(fechaNac);
        edadField.value = edad;
    } else {
        console.log("El valor de fechaNacField no se ha establecido");
    }

    function calcularEdad(fechaNac) {
        var hoy = new Date();
        var edad = hoy.getFullYear() - fechaNac.getFullYear();
        var m = hoy.getMonth() - fechaNac.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) {
            edad--;
        }
        return edad;
    }
};
	$(document).ready(function(){
        llenarSelectorEstado(document.getElementById("estado"),venezuela);

        $('#estado').val('<?php echo $row['estado']; ?>');
        llenarSelector(municipioSelector,buscarEstado(estadoSelector.value));
        $('#municipio').val('<?php echo $row['municipio']; ?>');
        llenarSelectorMunicipio(parroquiaSelector,buscarMunicipio(estadoSelector.value,municipioSelector.value));
        $('#parroquia').val('<?php echo $row['parroquia']; ?>');
	});		

  	// Obtener los selectores desplegables
  		
  	var estadoSelector = document.getElementById("estado");
 	var municipioSelector = document.getElementById("municipio");
  	var parroquiaSelector = document.getElementById("parroquia");

  	// Agregar un evento para actualizar los municipios cuando se selecciona un estado

    estadoSelector.addEventListener("change", function() {

        llenarSelector(municipioSelector,buscarEstado(estadoSelector.value)); 
    }); 

    // Agregar un evento para actualizar las parroquias cuando se selecciona un municipio
    municipioSelector.addEventListener("change", function() {
        llenarSelectorMunicipio(parroquiaSelector,buscarMunicipio(estadoSelector.value,municipioSelector.value)); 
           
    });     

    // Función para llenar un selector desplegable con opciones
    function llenarSelector(selector, opciones) {
        
        selector.innerHTML = "";
        
        for (var i = 0; i < opciones.length; i++) {
            var opcion = document.createElement("option");
            opcion.value = opciones[i].municipio;
            opcion.text = opciones[i].municipio;
            selector.add(opcion);
        }
    }
    function llenarSelectorMunicipio(selector, opciones) {
        
        selector.innerHTML = "";
        
        for (var i = 0; i < opciones.length; i++) {
            var opcion = document.createElement("option");
            opcion.value = opciones[i];
            opcion.text = opciones[i];
            selector.add(opcion);
        }
    }
    function llenarSelectorEstado(selector, opciones) {
        selector.innerHTML = "";
        for (var i = 0; i < opciones.length; i++) {
            var opcion = document.createElement("option");
            opcion.value = opciones[i].estado;
            opcion.text = opciones[i].estado;
            selector.add(opcion);
        }
    }
    function buscarEstado(estado){  

        for (var i = 0; i < venezuela.length; i++) {
                
            if(venezuela[i].estado == estado){
                return venezuela[i].municipios;
            }   
        }   
    }       
    function buscarMunicipio(estado,municipio){

        var arreglo = buscarEstado(estado);

        for (var i = 0; i < arreglo.length; i++) {
            
            if (arreglo[i].municipio == municipio) {
                
                return arreglo[i].parroquias;
            }   
        }   
    }

	function updateSecciones() {

var gradoSelect = document.getElementById("grado_e");
var seccionSelect = document.getElementById("seccion_e");
var selectedGrado = gradoSelect.value;

// Restaurar todas las opciones
seccionSelect.querySelectorAll("option").forEach(function(option) {
	option.style.display = "block";
});

if (selectedGrado == "Preescolar") {
	// Ocultar las opciones que no sean etapas
	seccionSelect.querySelectorAll("option:not([value^='E'])").forEach(function(option) {
		option.style.display = "none";
	});
} else {
	// Ocultar las opciones que no sean A, B, C o D
	seccionSelect.querySelectorAll("option:not([value='A']):not([value='B']):not([value='C']):not([value='D'])").forEach(function(option) {
		option.style.display = "none";
	});
}
}
	 	
	
</script>  
</body>

</html>
